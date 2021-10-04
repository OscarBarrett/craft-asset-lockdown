<?php
/**
 * Asset Lockdown plugin for Craft CMS 3.x
 *
 * Extends Craft CMS permissions to allow locking down access to the Assets Page but still allow uploading.
 *
 * @link      https://github.com/OscarBarrett/craft-asset-lockdown
 * @copyright Copyright (c) 2020 Oscar Barrett
 */

namespace oscarbarrett\assetlockdown;

use Craft;
use craft\base\Plugin;
use craft\services\Plugins;
use craft\services\UserPermissions;
use craft\events\PluginEvent;
use craft\events\RegisterCpNavItemsEvent;
use craft\events\RegisterUserPermissionsEvent;
use craft\web\twig\variables\Cp;

use yii\base\Event;
use yii\web\ForbiddenHttpException;

/**
 * Class AssetLockdown
 *
 * @author    Oscar Barrett
 * @package   AssetLockdown
 * @since     1.0.0
 *
 */
class AssetLockdown extends Plugin
{
    // Static Properties
    // =========================================================================

    /**
     * @var AssetLockdown
     */
    public static $plugin;

    // Public Properties
    // =========================================================================

    /**
     * @var string
     */
    public $schemaVersion = '1.0.0';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        Event::on(
            UserPermissions::class,
            UserPermissions::EVENT_REGISTER_PERMISSIONS,
            function(RegisterUserPermissionsEvent $event) {
                $event->permissions['Asset Lockdown'] = [
                    'viewAssetsPage' => [
                        'label' => 'View the Assets Page',
                    ],
                ];
            }
        );

        $request = Craft::$app->getRequest();

        if ($request->isCpRequest && !Craft::$app->user->checkPermission('viewAssetsPage')) {
            Event::on(Cp::class, Cp::EVENT_REGISTER_CP_NAV_ITEMS, function(RegisterCpNavItemsEvent $event) {
                // Remove Assets nav item
                $event->navItems = array_filter($event->navItems, function($navItem) {
                    return ($navItem['url'] !== 'assets');
                });
            });

            // Disallow requests to the assets page
            if (sizeof($request->segments) >= 1 && $request->segments[0] === 'assets') {
                throw new ForbiddenHttpException('You are not permitted to view the assets page.');
            }
        }
    }

    // Protected Methods
    // =========================================================================

}
