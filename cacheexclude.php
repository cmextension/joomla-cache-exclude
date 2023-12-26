<?php
/**
 * @package     plg_system_cacheexclude
 * @copyright   Copyright (C) 2023 CMExension
 * @license     GNU General Public License version 2 or later
 */

defined('_JEXEC') or die;

use Joomla\CMS\Plugin\CMSPlugin;
use Joomla\CMS\Factory;
use Joomla\CMS\Uri\Uri;

/**
 * Cache Exclude plugin.
 * Based on plg_system_cache.
 *
 * @package     plg_system_cacheexclude
 * @since       1.0.0
 */
class PlgSystemCacheExclude extends CMSPlugin
{
    /**
     * Application object.
     *
     * @var    JApplicationCms
     * @since  1.0.0
     */
    protected $app;

    /**
     * Constructor.
     *
     * @param   object  &$subject  The object to observe.
     * @param   array   $config    An optional associative array of configuration settings.
     *
     * @since   1.0.0
     */
    public function __construct(& $subject, $config)
    {
        parent::__construct($subject, $config);

        // Get the application if not done by JPlugin.
        if (!isset($this->app))
        {
            $this->app = Factory::getApplication();
        }
    }

    /**
     * onAfterRoute event.
     *
     * @return  void
     *
     * @since   1.0.0
     */
    public function onAfterRoute()
    {
        $app = Factory::getApplication();

        if (!$app->isClient('site')) return;

        $exclude = $this->shouldWeExclude();

        if ($exclude)
        {
            Factory::getConfig()->set('caching', 0);
        }
    }

    /**
     * Check if current page is excluded from cache.
     *
     * @return  boolean
     *
     * @since   1.0.0
     */
    private function shouldWeExclude()
    {
        if ($exclusions = $this->params->get('excluded_menu_item_ids', []))
        {
            // Get the current menu item.
            $active = $this->app->getMenu()->getActive();

            if ($active && $active->id && in_array((int) $active->id, (array) $exclusions))
            {
                return true;
            }
        }

        if ($exclusions = $this->params->get('excluded_urls', ''))
        {
            // Normalize line endings.
            $exclusions = str_replace(array("\r\n", "\r"), "\n", $exclusions);

            // Split them.
            $exclusions = explode("\n", $exclusions);

            if (!$exclusions) return false;

            $externalUri = Uri::getInstance()->toString();
            $internalUri = '/index.php?' . Uri::getInstance()->buildQuery($this->app->getRouter()->getVars());

            // Loop through each pattern.
            foreach ($exclusions as $exclusion)
            {
                // Make sure the exclusion has some content.
                if ($exclusion !== '')
                {
                    // Test both external and internal URI.
                    if (preg_match('#' . $exclusion . '#i', $externalUri . ' ' . $internalUri, $match))
                    {
                        return true;
                    }
                }
            }
        }

        return false;
    }
}