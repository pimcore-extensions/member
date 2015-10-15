<?php

namespace Member\Plugin;

use Pimcore\File;
use Pimcore\Model\Document;
use Pimcore\Model\Object\AbstractObject;
use Pimcore\Model\Object\ClassDefinition;
use Pimcore\Model\Object\Folder;
use Pimcore\Model\Translation;
use Pimcore\Model\User;
use Pimcore\Tool;

class Installer
{
    /**
     * @var User
     */
    protected $user;

    protected $baseDir;

    public function __construct($baseDir)
    {
        $this->baseDir = $baseDir;
    }

    /**
     * @param string $name
     * @return ClassDefinition
     * @throws \Exception
     */
    public function createClass($name)
    {
        $def = file_get_contents(sprintf('%s/class_%s.json', $this->baseDir, $name));
        $conf = json_decode($def, true);
        $layoutDefinitions = ClassDefinition\Service::generateLayoutTreeFromArray(
            $conf['layoutDefinitions']
        );
        if (!$layoutDefinitions) {
            throw new \Exception('Unable to generate class layout for ' . $name);
        }

        $class = ClassDefinition::create();
        $class->setName($name);
        $class->setUserOwner($this->getUser()->getId());
        $class->setLayoutDefinitions($layoutDefinitions);
        $class->setIcon($conf['icon']);
        $class->setAllowInherit($conf['allowInherit']);
        $class->setAllowVariants($conf['allowVariants']);
        $class->setParentClass($conf['parentClass']);
        $class->setPreviewUrl($conf['previewUrl']);
        $class->setPropertyVisibility($conf['propertyVisibility']);
        $class->save();

        return $class;
    }

    /**
     * @param string $name
     */
    public function removeClass($name)
    {
        $class = ClassDefinition::getByName($name);
        if ($class) {
            $class->delete();
        }
    }

    public function importDocuments()
    {
        $file = sprintf('%s/documents.json', $this->baseDir);
        $docs = new \Zend_Config_Json($file);

        foreach ($docs as $def) {
            $def = $def->toArray();

            $parent = Document::getByPath($def['parent']);
            unset($def['parent']);
            if (!$parent) {
                $parent = Document::getById(1);
            }

            $path = $parent->getFullPath() . '/' . $def['key'];
            if (Document\Service::pathExists($path)) {
                $doc = Document::getByPath($path);
            } else {
                $docClass = '\\Pimcore\\Model\\Document\\' . ucfirst($def['type']);
                /** @var Document $doc */
                $doc = $docClass::create($parent->getId(), $def, false);
                $doc->setUserOwner(self::getUser()->getId());
                $doc->setUserModification(self::getUser()->getId());
            }
            $doc->setValues($def);
            $doc->setPublished(true);
            $doc->save();
        }
    }

    public function removeDocuments()
    {
        $file = sprintf('%s/documents.json', $this->baseDir);
        $docs = new \Zend_Config_Json($file);
        $root = reset($docs->toArray());
        $doc = Document::getByPath('/' . ltrim($root['parent'] . '/' . $root['key'], '/'));
        if ($doc) {
            $doc->delete();
        }
    }

    /**
     * @param string $key
     * @param AbstractObject|null $parent
     * @return Folder
     */
    public function createObjectFolder($key, $parent = null)
    {
        if ($parent instanceof AbstractObject) {
            $parent = $parent->getId();
        }

        $folder = Folder::create(array(
            'o_parentId' => ($parent !== null) ? $parent : 1,
            'o_creationDate' => time(),
            'o_userOwner' => $this->getUser()->getId(),
            'o_userModification' => $this->getUser()->getId(),
            'o_key' => $key,
            'o_published' => true,
        ));

        return $folder;
    }

    /**
     * @param string $path
     */
    public function removeObjectFolder($path)
    {
        $folder = Folder::getByPath($path);
        if ($folder) {
            $folder->delete();
        }
    }

    public function addClassmap($from, $to)
    {
        $classmapXml = PIMCORE_CONFIGURATION_DIRECTORY . '/classmap.xml';
        try {
            $conf = new \Zend_Config_Xml($classmapXml);
            $classmap = $conf->toArray();
        } catch (\Exception $e) {
            $classmap = array();
        }
        $classmap[$from] = $to;
        $writer = new \Zend_Config_Writer_Xml(array(
            'config' => new \Zend_Config($classmap),
            'filename' => $classmapXml
        ));
        $writer->write();
    }

    public function removeClassmap($from)
    {
        $classmapXml = PIMCORE_CONFIGURATION_DIRECTORY . '/classmap.xml';
        try {
            $conf = new \Zend_Config_Xml($classmapXml);
            $classmap = $conf->toArray();
            unset($classmap[$from]);
            $writer = new \Zend_Config_Writer_Xml(array(
                'config' => new \Zend_Config($classmap),
                'filename' => $classmapXml
            ));
            $writer->write();
        } catch (\Exception $e) {
        }
    }

    public function createConfig($name)
    {
        $src = sprintf('%s/config.xml', $this->baseDir);
        $dest = sprintf('%s/plugins/%s/config.xml', PIMCORE_WEBSITE_VAR, $name);
        if (!is_dir(dirname($dest))) {
            if (!@mkdir(dirname($dest), 0777, true)) {
                throw new \Exception('Unable to create plugin config directory');
            }
        }

        if (!@copy($src, $dest)) {
            throw new \Exception('Unable to copy plugin config');
        }
    }

    public function removeConfig($name)
    {
        $dest = PIMCORE_WEBSITE_VAR . '/plugins/' . $name . '/config.xml';
        @unlink($dest);
        @rmdir(basename($dest));
    }

    public function importTranslations()
    {
        foreach (Tool::getValidLanguages() as $lang) {
            $src = sprintf('%s/i18n/%s.csv', $this->baseDir, $lang);
            $csv = new \Csv_Reader($src, new \Csv_Dialect([
                'delimiter' => ',',
                'quotechar' => '"',
            ]));
            foreach ($csv as $row) {
                $t = Translation\Website::getByKey($row[0], true);
                $t->addTranslation($lang, $row[1]);
                $t->save();
            }
        }
    }

    /**
     * @return User
     */
    protected function getUser()
    {
        if (!$this->user) {
            $this->user = \Zend_Registry::get('pimcore_admin_user');
        }

        return $this->user;
    }
}
