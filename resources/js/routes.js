import Home from './components/Home.vue';

import Catalog from './components/catalog/Catalog.vue';
import Elements from './components/catalog/Elements.vue';
import ElementCreate from './components/catalog/ElementCreate.vue';
import ElementEdit from './components/catalog/ElementEdit.vue';

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
        name: 'Elements',
        component: Elements
    },
    {
        path: '/elements/create/:category?',
        name: 'ElementCreate',
        component: ElementCreate
    },
    {
        path: '/element/:id/edit',
        name: 'ElementEdit',
        component: ElementEdit
    },
];