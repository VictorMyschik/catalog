require('./bootstrap');

import {createApp} from 'vue';

import mrp from './components/MrPopupForm.vue';
import mrTable from "./components/MrTable.vue";

createApp({
    components: {
        mrp,
        mrTable
    }
}).mount('#app');