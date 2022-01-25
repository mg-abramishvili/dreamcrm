import Home from './components/Home.vue';

import Catalog from './components/catalog/Catalog.vue';
import CategoryElements from './components/catalog/CategoryElements.vue';
import ElementCreate from './components/catalog/ElementCreate.vue';
import ElementEdit from './components/catalog/ElementEdit.vue';
import Boxes from './components/catalog/Boxes.vue';
import BoxEdit from './components/catalog/BoxEdit.vue';
import CatalogSearch from './components/catalog/CatalogSearch.vue';

import Calculations from './components/calculations/Calculations.vue';
import Calculation from './components/calculations/Calculation.vue';
import CalculationCreate from './components/calculations/CalculationCreate.vue';

import Offers from './components/offers/Offers.vue';
import Offer from './components/offers/Offer.vue';

import Tasks from './components/tasks/Tasks.vue';

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
        path: '/tasks',
        name: 'Tasks',
        component: Tasks
    },
];