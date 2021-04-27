<?php

namespace App\EventsListener;

use App\Lib\MenuManager\Builder;
use App\Events\ViewRenderParametersEvent;

class MenuBuilderListener
{
    protected Builder $builder;

    /**
     * MenuBuilderListener constructor.
     *
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->builder = $builder;
    }

    public function onViewRenderParametersEvent(ViewRenderParametersEvent $event)
    {
        $viewMode = $event->getController()->getAuth()->isViewMode();
        $loggedUser = $event->getController()->getAuth()->isLogged();

        $headerMenu = $this->builder->menu('headerMenu')
            ->setOptions(
                [
                    'public' => true,
                ]
            )
            ->addItem('Trade')->setVisible(false)
            ->addItem('Analytics')->setVisible(false)
            ->addItem('Crypto Trust')->setAttributes(['class' => 'menu-crypto'])
            ->addItem('Products')->setAttributes(['class' => 'menu-products'])
            ->addItem('Market News')->setAttributes(['class' => 'menu-market'])
            ->build($viewMode);

        $userMenu = $this->builder->menu('userMenu')
            ->setOptions(
                [
                    'visible' => $loggedUser,
                ]
            )
            ->addItem('My Account')->setRoute('route_user_settings')
            ->addItem('Dashboard')->setRoute('route_user_dashboard')
            ->addItem('My Products')->setRoute('route_myproducts')
            ->addItem('Build Portfolio')->setRoute('route_portfolio')
            ->addItem('Deposit')->setRoute('route_deposit')
            ->addItem('Withdrawal')->setRoute('route_withdrawal')
            ->addItem('API Dashboard')->setRoute('route_analytics_index')
            ->build($viewMode);

        $currentRoute = $event->getController()->getRequest()->get('_route');
        $dashboardMenu = $this->builder->menu('subMenu')
            ->setOptions(
                [
                    'visible' => $loggedUser,
                    'routes' => [
                        'route_user_dashboard',
                        'route_portfolio',
                        'route_myproducts',
                        'route_user_settings',
                        'route_deposit',
                        'route_withdrawal',
                        'route_analytics_all',
                        'route_convert',
                    ],
                ]
            )
            ->addItem('Dashboard')->setRoute('route_user_dashboard')->setSelected($currentRoute == 'route_user_dashboard')
            ->addItem('Build Portfolio')->setRoute('route_portfolio')->setSelected($currentRoute == 'route_portfolio')
            ->addItem('My Products')->setRoute('route_myproducts')->setSelected($currentRoute == 'route_myproducts')
            ->addItem('My Account')->setRoute('route_user_settings')->setSelected($currentRoute == 'route_user_settings')
            ->addItem('Deposit')->setRoute('route_deposit')->setSelected($currentRoute == 'route_deposit')->setPublic(true)
            ->addItem('Withdrawal')->setRoute('route_withdrawal')->setSelected($currentRoute == 'route_withdrawal')->setPublic(true)
            ->addItem('API')->setRoute('route_analytics_all')->setSelected($currentRoute == 'route_analytics_all')->setPublic(true)
            ->build($viewMode);

        $myAccountMenu = $this->builder->menu('subMenu')
            ->setOptions(
                [
                    'visible' => $loggedUser,
                    'routes' => [
                        'route_user_settings',
                        'route_documents',
                    ],
                ]
            )
            ->addItem('Dashboard')->setRoute('route_user_dashboard')->setSelected($currentRoute == 'route_user_dashboard')
            ->addItem('Profile')->setRoute('route_user_settings')->setSelected($currentRoute == 'route_user_settings')
            ->addItem('Documents')->setRoute('route_documents')->setSelected($currentRoute == 'route_documents')->setPublic(true)
            ->build($viewMode);

        $sidebarMenu = $this->builder->menu('sidebarMenu')
            ->addItem('Crypto Trust')->setAttributes(['class'=>'smenu-crypto'])
            ->addItem('About')->setRoute('route_aboutus')->setParent('Crypto Trust')
            ->addItem('Documents')->setRoute('route_documents')->setParent('Crypto Trust')
            ->addItem('FAQ')->setRoute('route_faq')->setParent('Crypto Trust')
            ->addItem('Contacts')->setRoute('route_contacts')->setParent('Crypto Trust')

            ->addItem('Products')->setAttributes(['class'=>'smenu-products'])
            ->addItem('All Products')->setRoute('route_product_list')->setParent('Products')
            ->addItem('Conservative Trust')->setRoute('route_product_item', ['name' => 'conservative'])->setParent('Products')
            ->addItem('Classic Trust')->setRoute('route_product_item', ['name' => 'classic'])->setParent('Products')
            ->addItem('Confident Trust')->setRoute('route_product_item', ['name' => 'confident'])->setParent('Products')
            ->addItem('Individual Portfolio')->setRoute('route_product_item', ['name' => 'individual'])->setParent('Products')
            ->addItem('Pricing Plans')->setRoute('route_pricing')->setParent('Products')
            ->addItem('Comparative Pricing Tables')->setRoute('route_full_pricing')->setParent('Products')

            ->addItem('Market News')->setAttributes(['class'=>'smenu-market'])
            ->addItem('Crypto Rates')->setRoute('route_rates')->setParent('Market News')
            ->addItem('Market Feed')->setRoute('route_newsfeed')->setParent('Market News')
            ->addItem('Crypto Facts')->setRoute('route_cryptofeed')->setParent('Market News')
            ->addItem('Post page')->setRoute('route_home')->setParent('Market News')->setVisible(false)

            ->build($viewMode);

        $event->addParameter(
            'siteNavBars',
            array_merge(
                $headerMenu,
                $userMenu,
                $dashboardMenu,
                $myAccountMenu,
                $sidebarMenu
            )
        );
    }
}
