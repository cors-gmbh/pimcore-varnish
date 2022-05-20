/**
 * CORS GmbH.
 *
 * This source file is available under two different licenses:
 * - GNU General Public License version 3 (GPLv3)
 * - Pimcore Commercial License (PCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CORS GmbH (https://www.cors.gmbh)
 * @license    https://www.cors.gmbh/license     GPLv3 and PCL
 */

pimcore.registerNS('pimcore.plugin.cors_varnish');

pimcore.plugin.cors_varnish = Class.create(pimcore.plugin.admin, {
    getClassName: function () {
        return 'pimcore.plugin.cors_varnish';
    },

    initialize: function () {
        pimcore.plugin.broker.registerPlugin(this);
    },

    postOpenObject: function (tab) {
        debugger;
        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('clear_cache')) {
            this._enrichElement(tab, 'object');

            pimcore.layout.refresh();
        }
    },

    postOpenDocument: function (tab) {
        debugger;
        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('clear_cache')) {
            this._enrichElement(tab, 'document');

            pimcore.layout.refresh();
        }
    },

    _enrichElement: function (tab, type) {
        tab.toolbar.insert(tab.toolbar.items.length, '-');
        tab.toolbar.insert(tab.toolbar.items.length, {
            text: t('cors_varnish_purge_cache'),
            scale: 'medium',
            iconCls: 'pimcore_nav_icon_clear_cache',
            handler: function () {
                this.clearCache(tab.id, type)
            }.bind(this, tab)
        });
    },

    pimcoreReady: function (params, broker) {

        var user = pimcore.globalmanager.get('user');

        if (user.isAllowed('clear_cache')) {

            var purgeCache = new Ext.Action({
                text: t('cors_varnish_purge_cache'),
                iconCls: 'pimcore_nav_icon_clear_cache',
                handler: this.purgeCache.bind(this)
            });

            var cacheMenu = layoutToolbar.settingsMenu.down('#pimcore_menu_settings_cache');

            if (cacheMenu) {
                cacheMenu.menu.add(purgeCache);
            }
        }
    },

    purgeCache: function () {
         Ext.Ajax.request({
            url: Routing.generate('cors_varnish_purge_cache'),
            method: "DELETE",
        });
    },

    clearCache: function (id, type) {
        Ext.Ajax.request({
            url: Routing.generate('cors_varnish_clear_element_cache'),
            method: "DELETE",
            params: {
                id: id,
                type: type
            }
        });
    },
});

new pimcore.plugin.cors_varnish();