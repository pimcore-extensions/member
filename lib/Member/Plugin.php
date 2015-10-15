<?php

namespace Member;

use Member\Plugin\Config;
use Member\Plugin\Installer;
use Pimcore\API\Plugin as PluginLib;
use Pimcore\Model\Object\ClassDefinition;

class Plugin extends PluginLib\AbstractPlugin implements PluginLib\PluginInterface
{
    /**
     * @var \Zend_Translate
     */
    protected static $_translate;

    public function init()
    {
        parent::init();

        \Zend_Controller_Action_HelperBroker::addPrefix('Member_Controller_Action_Helper');

        // attach default listeners
        \Pimcore::getEventManager()->attach('member.register.validate',
            ['\\Member\\Listener\\Register', 'validate'], 0);

        if (Config::get('actions')->postRegister) {
            \Pimcore::getEventManager()->attach('member.register.post',
                ['\\Member\\Listener\\Register', Config::get('actions')->postRegister], 0);
        }
    }

    /**
     * @return bool
     */
    public static function needsReloadAfterInstall()
    {
        return true;
    }

    public static function install()
    {
        try {
            $installer = new Installer(PIMCORE_PLUGINS_PATH . '/Member/install');

            $installer->createConfig('member');
            $installer->createObjectFolder('members');
            $installer->createClass('Member');
            $installer->addClassmap('Object_Member', '\\Member');
            $installer->importTranslations();

        } catch (\Exception $e) {
            \Logger::crit($e);
            self::uninstall(); // revert installation
            return self::getTranslate()->_('plugin_member_install_failed');
        }

        return self::getTranslate()->_('plugin_member_install_successful');
    }

    public static function uninstall()
    {
        try {
            $installer = new Installer(PIMCORE_PLUGINS_PATH . '/Member/install');

            $installer->removeObjectFolder('/members');
            $installer->removeClass('Member');
            $installer->removeClassmap('Object_Member');
            $installer->removeConfig('member');

        } catch (\Exception $e) {
            \Logger::crit($e);
            return self::getTranslate()->_('plugin_member_uninstall_failed');
        }

        return self::getTranslate()->_('plugin_member_uninstall_successful');
    }

    public static function isInstalled()
    {
        $memberClass = ClassDefinition::getByName('Member');
        if ($memberClass) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    public static function getTranslationFileDirectory()
    {
        return PIMCORE_PLUGINS_PATH . '/Member/static/texts';
    }

    /**
     * @param string $language
     * @return string path to the translation file relative to plugin directory
     */
    public static function getTranslationFile($language)
    {
        if (is_file(self::getTranslationFileDirectory() . "/$language.csv")) {
            return "/Member/static/texts/$language.csv";
        }

        return '/Member/static/texts/en.csv';
    }

    /**
     * @return \Zend_Translate
     */
    public static function getTranslate()
    {
        if (self::$_translate instanceof \Zend_Translate) {
            return self::$_translate;
        }

        try {
            $lang = \Zend_Registry::get('Zend_Locale')->getLanguage();
        } catch (\Exception $e) {
            $lang = 'en';
        }

        self::$_translate = new \Zend_Translate(
            'csv',
            PIMCORE_PLUGINS_PATH . self::getTranslationFile($lang),
            $lang,
            array('delimiter' => ',')
        );

        return self::$_translate;
    }
}
