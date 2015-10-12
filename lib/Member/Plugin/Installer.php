<?php

namespace Member\Plugin;

use Pimcore\Model\Object\AbstractObject;
use Pimcore\Model\Object\ClassDefinition;
use Pimcore\Model\Object\Folder;
use Pimcore\Model\User;

class Installer
{
    /**
     * @var User
     */
    protected $_user;

    /**
     * @param string $name
     * @return ClassDefinition
     * @throws \Exception
     */
    public function createClass($name)
    {
        $def = file_get_contents(PIMCORE_PLUGINS_PATH . "/Member/install/class_$name.json");
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

    /**
     * @param string $key
     * @param AbstractObject|null $root
     * @return Folder
     */
    public function createObjectFolder($key, $root = null)
    {
        if ($root instanceof AbstractObject) {
            $root = $root->getId();
        }

        $folder = Folder::create(array(
            'o_parentId' => ($root !== null) ? $root : 1,
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

    /**
     * @return User
     */
    protected function getUser()
    {
        if (!$this->_user) {
            $this->_user = \Zend_Registry::get('pimcore_admin_user');
        }

        return $this->_user;
    }
}
