import Home from './components/Home.vue';

import Catalog from './components/catalog/Catalog.vue';
import CategoryElements from './components/catalog/CategoryElements.vue';
import ElementCreate from './components/catalog/ElementCreate.vue';
import ElementEdit from './components/catalog/ElementEdit.vue';
import Boxes from './components/catalog/Boxes.vue';
import CatalogSearch from './components/catalog/CatalogSearch.vue';

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
        path: '/catalog/:category/create',
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
        path: '/catalog/search',
        name: 'CatalogSearch',
        component: CatalogSearch
    },
];