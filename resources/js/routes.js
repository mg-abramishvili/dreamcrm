import Home from './components/Home.vue';

import Catalog from './components/catalog/Catalog.vue';
import CategoryCreate from './components/catalog/CategoryCreate.vue';
import CategoryElements from './components/catalog/CategoryElements.vue';
import ElementCreate from './components/catalog/ElementCreate.vue';
import ElementEdit from './components/catalog/ElementEdit.vue';
import Boxes from './components/catalog/Boxes.vue';
import BoxCreate from './components/catalog/BoxCreate.vue';
import BoxEdit from './components/catalog/BoxEdit.vue';
import CatalogSearch from './components/catalog/CatalogSearch.vue';

import Calculations from './components/calculations/Calculations.vue';
import Calculation from './components/calculations/Calculation.vue';
import CalculationCreate from './components/calculations/CalculationCreate.vue';

import Offers from './components/offers/Offers.vue';
import Offer from './components/offers/Offer.vue';

import Tasks from './components/tasks/Tasks.vue';

import Clients from './components/clients/Clients.vue';
import ClientCreate from './components/clients/ClientCreate.vue';
import Client from './components/clients/Client.vue';

import Users from './components/users/Users.vue';
import User from './components/users/User.vue';

import Stock from './components/stock/Index.vue';
import StockCategory from './components/stock/Category.vue';
import StockCategoryCreate from './components/stock/CategoryCreate.vue';
import StockCategoryEdit from './components/stock/CategoryEdit.vue';
import StockItem from './components/stock/Item.vue';
import StockItemCreate from './components/stock/ItemCreate.vue';

import Notifications from './components/notifications/Index.vue';

export const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home
    },
    {
        path: '/catalog',
        name: 'Catalog',
        component: Catalog
    },
    {
        path: '/catalog/category/create',
        name: 'CategoryCreate',
        component: CategoryCreate
    },
    {
        path: '/catalog/:category/elements',
        name: 'CategoryElements',
        component: CategoryElements
    },
    {
        path: '/catalog/:category?/create',
        name: 'ElementCreate',
        component: ElementCreate
    },
    {
        path: '/element/:id/edit',
        name: 'ElementEdit',
        component: ElementEdit
    },
    {
        path: '/catalog/boxes',
        name: 'Boxes',
        component: Boxes
    },
    {
        path: '/box/create',
        name: 'BoxCreate',
        component: BoxCreate
    },
    {
        path: '/box/:id/edit',
        name: 'BoxEdit',
        component: BoxEdit
    },
    {
        path: '/catalog/search',
        name: 'CatalogSearch',
        component: CatalogSearch
    },
    {
        path: '/calculations',
        name: 'Calculations',
        component: Calculations
    },
    {
        path: '/calculations/create',
        name: 'CalculationCreate',
        component: CalculationCreate
    },
    {
        path: '/calculation/:id',
        name: 'Calculation',
        component: Calculation
    },
    {
        path: '/offers',
        name: 'Offers',
        component: Offers
    },
    {
        path: '/offer/:id',
        name: 'Offer',
        component: Offer
    },
    {
        path: '/tasks/:board_id?',
        name: 'Tasks',
        component: Tasks
    },
    {
        path: '/clients',
        name: 'Clients',
        component: Clients
    },
    {
        path: '/clients/create',
        name: 'ClientCreate',
        component: ClientCreate
    },
    {
        path: '/client/:id',
        name: 'Client',
        component: Client
    },
    {
        path: '/users',
        name: 'Users',
        component: Users
    },
    {
        path: '/user/:uid',
        name: 'User',
        component: User
    },
    {
        path: '/stock',
        name: 'Stock',
        component: Stock
    },
    {
        path: '/stock/category/:category_id',
        name: 'StockCategory',
        component: StockCategory
    },
    {
        path: '/stock/:category?/create',
        name: 'StockCategoryCreate',
        component: StockCategoryCreate
    },
    {
        path: '/stock/category/:category_id/edit',
        name: 'StockCategoryEdit',
        component: StockCategoryEdit
    },
    {
        path: '/stock/item/:id',
        name: 'StockItem',
        component: StockItem
    },
    {
        path: '/stock/items/create',
        name: 'StockItemCreate',
        component: StockItemCreate
    },
    {
        path: '/notifications',
        name: 'Notifications',
        component: Notifications
    },
];