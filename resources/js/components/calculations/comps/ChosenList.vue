<template>
    <div v-if="categories.length">
        <div class="card card-bordered mb-2">
            <div class="card-body py-2 px-3">
                <small style="color: rgb(136, 136, 136);">Корпус</small>
                <div v-if="selectedBox && selectedBox.id > 0" class="row align-items-center">
                    <div class="col-8">
                        <strong>{{ selectedBox.name }}</strong>

                        <button @click="boxStockListToggle()" class="btn btn-sm btn-default">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </button>
                    </div>
                    <div class="col-4 text-end">
                        <strong class="text-primary">{{ selectedBox.price | currency }} ₽</strong>
                    </div>
                    <div v-if="selectedBox.manager_description" class="col-12 mt-2">
                        <div class="border border-warning rounded d-flex align-items-center" style="font-size: 14px; padding: 10px;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-exclamation-circle d-block me-2 text-warning" viewBox="0 0 16 16">
                                <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                            </svg>

                            <span>{{ selectedBox.manager_description }}</span>
						</div>
                    </div>
                    <div class="col-12">
                        <ul id="calculation-stock-list_box" class="calculation-stock-list">
                            <li v-for="stockItem in selectedBox.stock_items" :key="stockItem.id">
                                <template v-if="stockItem.pivot && stockItem.pivot.quantity > 1">
                                    {{ stockItem.pivot.quantity }} &times; 
                                </template>
                                {{ stockItem.name }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div v-for="category in categories" :key="category.id">
            <div v-if="catalogItemsByCategory(category) && catalogItemsByCategory(category).length" :key="'category_' + category.id" class="card card-bordered mb-2">
                <div class="card-body py-2 px-3">
                    <small style="color: rgb(136, 136, 136);">{{ category.name }}</small>
                    <div v-for="(item, index) in catalogItemsByCategory(category)" :key="item.id" class="row align-items-center">
                        <div class="col-8">
                            <strong>{{ item.name }}</strong>
                            
                            <button @click="stockListToggle(item.id, index)" v-if="item.stock_items && item.stock_items.length" class="btn btn-sm btn-default">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-chevron-down" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M1.646 4.646a.5.5 0 0 1 .708 0L8 10.293l5.646-5.647a.5.5 0 0 1 .708.708l-6 6a.5.5 0 0 1-.708 0l-6-6a.5.5 0 0 1 0-.708z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="col-4 text-end">
                            <strong v-if="item.price > 0" class="text-primary">{{ item.price | currency }} ₽</strong>
                        </div>
                        <div class="col-12">
                            <ul :id="'calculation-stock-list_' + item.id + '_' + index" class="calculation-stock-list">
                                <li v-for="stockItem in item.stock_items" :key="stockItem.id">
                                    <template v-if="stockItem.pivot.quantity > 1">
                                        {{ stockItem.pivot.quantity }} &times; 
                                    </template>
                                    {{ stockItem.name }}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['categories', 'catalogItems', 'selectedBox', 'selectedCatalogItems'],
        data() {
            return {
                //
            }
        },
        methods: {
            catalogItemsByCategory(category) {
                let categoryItems = category.items.filter(item => item.category_id == category.id)
                let categoryItemsSelected = this.selectedCatalogItems[category.slug]
                let categoryItemsFiltered = []

                if(!categoryItemsSelected) {
                    return
                }

                categoryItemsSelected.forEach((itemSelected) => {
                    if(categoryItems.find(item => item.id === itemSelected.id)) {
                        categoryItemsFiltered.push(categoryItems.find(item => item.id === itemSelected.id))
                    }
                })

                return categoryItemsFiltered
            },
            boxStockListToggle() {
                let element = document.getElementById('calculation-stock-list_box')
                
                if(!element) {
                    return
                }

                if(window.getComputedStyle(element, null).display == 'none') {
                    return element.style.display = "block"
                }

                if(window.getComputedStyle(element, null).display == 'block') {
                    return element.style.display = "none"
                }
            },
            stockListToggle(id, index) {
                let element = document.getElementById('calculation-stock-list_' + id + '_' + index)
                
                if(!element) {
                    return
                }

                if(window.getComputedStyle(element, null).display == 'none') {
                    return element.style.display = "block"
                }

                if(window.getComputedStyle(element, null).display == 'block') {
                    return element.style.display = "none"
                }
            },
        },
    }
</script>