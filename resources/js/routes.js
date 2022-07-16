import Home from './components/Home.vue';

import Catalog from './components/catalog/Index.vue';
import CatalogTypes from './components/catalog/Types.vue';
import CatalogTypeCreate from './components/catalog/TypeCreate.vue';
import CatalogTypeEdit from './components/catalog/TypeEdit.vue';
import CatalogCategory from './components/catalog/Category.vue';
import CatalogCategoryCreate from './components/catalog/CategoryCreate.vue';
import CatalogCategoryEdit from './components/catalog/CategoryEdit.vue';
import CatalogItemCreate from './components/catalog/ItemCreate.vue';
import CatalogItemEdit from './components/catalog/ItemEdit.vue';
import CatalogBoxes from './components/catalog/Boxes.vue';
import CatalogBoxMaster from './components/catalog/BoxMaster.vue';
import CatalogSearch from './components/catalog/Search.vue';

import Calculations from './components/calculations/Index.vue';
import Calculation from './components/calculations/Calculation.vue';
import CalculationMaster from './components/calculations/Master.vue';

import Projects from './components/projects/Index.vue';
import Project from './components/projects/Project.vue';
import ProjectCreate from './components/projects/Create.vue';

import Tasks from './components/tasks/Tasks.vue';

import Clients from './components/clients/Clients.vue';
import ClientCreate from './components/clients/ClientCreate.vue';
import Client from './components/clients/Client.vue';

import Users from './components/users/Users.vue';
import User from './components/users/User.vue';

import Stock from './components/stock/Index.vue';
import StockCategory from './components/stock/category/Category.vue';
import StockCategoryCreate from './components/stock/category/Create.vue';
import StockCategoryEdit from './components/stock/category/Edit.vue';
import StockItem from './components/stock/item/Item.vue';
import StockItemCreate from './components/stock/item/Create.vue';
import StockNeeds from './components/stock/needs/Index.vue';
import StockLatest from './components/stock/latest/Index.vue';

import Reserves from './components/reserves/Index.vue';

import Notifications from './components/notifications/Index.vue';

import Productions from './components/productions/Index.vue';
import ProductionCreate from './components/productions/Create.vue';
import Production from './components/productions/Production.vue';

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
        path: '/catalog/types',
        name: 'CatalogTypes',
        component: CatalogTypes
    },
    {
        path: '/catalog/types/create',
        name: 'CatalogTypeCreate',
        component: CatalogTypeCreate
    },
    {
        path: '/catalog/type/:id/edit',
        name: 'CatalogTypeEdit',
        component: CatalogTypeEdit
    },
    {
        path: '/catalog/category/:id',
        name: 'CatalogCategory',
        component: CatalogCategory
    },
    {
        path: '/catalog/categories/create',
        name: 'CatalogCategoryCreate',
        component: CatalogCategoryCreate
    },
    {
        path: '/catalog/category/:id/edit',
        name: 'CatalogCategoryEdit',
        component: CatalogCategoryEdit
    },
    
    {
        path: '/catalog/:category_id?/item/create',
        name: 'CatalogItemCreate',
        component: CatalogItemCreate
    },
    {
        path: '/catalog/item/:id/edit',
        name: 'CatalogItemEdit',
        component: CatalogItemEdit
    },
    {
        path: '/catalog/boxes',
        name: 'CatalogBoxes',
        component: CatalogBoxes
    },
    {
        path: '/catalog/boxes/master/:id?',
        name: 'CatalogBoxMaster',
        component: CatalogBoxMaster
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
        path: '/calculations/master/:id?',
        name: 'CalculationMaster',
        component: CalculationMaster
    },
    {
        path: '/calculation/:id',
        name: 'Calculation',
        component: Calculation
    },
    {
        path: '/projects',
        name: 'Projects',
        component: Projects
    },
    {
        path: '/project/:id',
        name: 'Project',
        component: Project
    },
    {
        path: '/projects/create/:calculation_id',
        name: 'ProjectCreate',
        component: ProjectCreate
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
        path: '/stock/needs',
        name: 'StockNeeds',
        component: StockNeeds
    },
    {
        path: '/stock/latest',
        name: 'StockLatest',
        component: StockLatest
    },
    {
        path: '/reserves',
        name: 'Reserves',
        component: Reserves
    },
    {
        path: '/notifications',
        name: 'Notifications',
        component: Notifications
    },
    {
        path: '/productions',
        name: 'Productions',
        component: Productions
    },
    {
        path: '/productions/create/:project_id',
        name: 'ProductionCreate',
        component: ProductionCreate
    },
    {
        path: '/production/:id',
        name: 'Production',
        component: Production
    },
];