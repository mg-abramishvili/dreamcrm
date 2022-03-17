<template>
    <div v-if="categories && categories.length">
        <div v-for="category in categories" :key="category.id">
            <div v-if="catalogItemsByCategory(category) && catalogItemsByCategory(category).length" :key="'category_' + category.id" class="card card-bordered mb-2">
                <div class="card-body py-2 px-3">
                    <small style="color: rgb(136, 136, 136);">{{ category.name }}</small>
                    <div v-for="item in catalogItemsByCategory(category)" :key="item.id" class="row align-items-center">
                        <div class="col-8">
                            <strong class="d-block">{{ item.name }}</strong>
                        </div>
                        <div class="col-4 text-end">
                            <strong class="text-primary">{{ item.price | currency }} ₽</strong>
                        </div>
                        <div class="col-12">
                            <ul class="calculation-stock-list">
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
        props: ['categories', 'catalogItems', 'selectedCatalogItems'],
        data() {
            return {
                //
            }
        },
        methods: {
            catalogItemsByCategory(category) {
                let categoryItems = this.catalogItems.filter(item => item.category_id == category.id)
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
        },
    }
</script>