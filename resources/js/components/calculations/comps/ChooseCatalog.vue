<template>
    <div class="mb-4">
        <loader v-if="views.loading"></loader>

        <div v-else v-for="category in categories" :key="'category_' + category.id">
            <div v-show="views.category == category.id && catalogItems.filter(item => item.category_id == category.id)">
                <div class="calculation-left-block-main-label">
                    <strong>{{ category.name }}</strong>
                    <button @click="addCatalogItem(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                </div>

                <div v-for="(catalogItem, index) in selected.catalogItems[category.slug]" :key="index" style="position: relative;">
                    <select v-model="catalogItem.id" class="form-select form-select-lg mt-2 mb-3">
                        <template v-for="catalogItem in catalogItems">
                            <option v-if="catalogItem.category_id == category.id" :key="'catalogItem_' + catalogItem.id" :value="catalogItem.id">
                                {{ catalogItem.name }} <template v-if="catalogItem.price > 0">&mdash; {{ catalogItem.price | currency }} ₽</template>
                            </option>
                        </template>
                    </select>
                    <button v-if="index > 0 && selected.catalogItems[category.slug].length > 1" @click="deleteCatalogItem(catalogItem.id, category.slug)" class="btn btn-sm btn-outline-danger" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 0; width: 20px; margin-right: -21px;">&ndash;</button>
                </div>

                <div class="mt-4">
                    <button @click="prevCategory(category)" class="btn btn-outline-primary">Назад</button>
                    <button @click="nextCategory(category)" class="btn btn-outline-primary">Далее</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['box_id'],
        data() {
            return {
                categories: [],
                catalogItems: [],

                selected: {
                    catalogItems: {}
                },

                views: {
                    loading: true,
                    category: '',
                }
            }
        },
        watch: {
            box_id: {
                handler() {
                    this.selected.catalogItems = {}

                    this.loadCategories()
                    this.loadCatalogItems()
                }
            },
            selected: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.selected.catalogItems)) {
                        if(category[1] && category[1].length > 0) {
                            category[1].forEach((i) => {
                                if(i.id != null) {
                                    i.price = parseInt(this.catalogItems.filter(item => item.id == i.id)[0].price)
                                    i.pre_rub = parseInt(this.catalogItems.filter(item => item.id == i.id)[0].pre_rub)
                                    i.pre_usd = parseInt(this.catalogItems.filter(item => item.id == i.id)[0].pre_usd)
                                }
                            })
                        }
                    }
                    this.$parent.selected.catalogItems = this.selected.catalogItems
                }
            }
        },
        methods: {
            loadCategories() {
                axios
                .get('/api/catalog/categories')
                .then((response => {
                    this.categories = response.data
                    this.$parent.categories = response.data

                    response.data.forEach(category => {
                        this.$set(this.selected.catalogItems, category.slug, [])
                        this.addCatalogItem(category.slug)
                    })

                    this.views.category = response.data[0].id
                }))
            },
            loadCatalogItems() {
                if(!this.box_id) {
                    return
                }

                axios
                .get(`/api/catalog/items/box/${this.box_id}`)
                .then((response => {
                    this.catalogItems = response.data
                    this.$parent.catalogItems = response.data
                }))
            },
            addCatalogItem(categorySlug) {
                let checkEmpty = this.selected.catalogItems[categorySlug].filter(item => item.id === null)
                if (checkEmpty.length >= 1 && this.selected.catalogItems[categorySlug].length > 0) {
                    return
                }
                this.selected.catalogItems[categorySlug].push({
                    id: null,
                    price: 0,
                    pre_rub: 0,
                    pre_usd: 0
                })

                this.views.loading = false
            },
            deleteCatalogItem(itemID, categorySlug) {
                var index = this.selected.catalogItems[categorySlug].map(item => { return item.id }).indexOf(itemID)
                this.selected.catalogItems[categorySlug].splice(index, 1)
            },
            prevCategory(category) {
                var index = this.categories.indexOf(category)
                if(index > 0 && index < this.categories.length + 1) {
                    this.views.category = this.categories[index - 1].id
                } else {
                    this.$parent.views.step = 'box'
                }
            },
            nextCategory(category) {                
                console.log(this.categories)

                let index = this.categories.indexOf(category)
                let nextCategory = this.categories[index + 1]

                if(index >= 0 && index < this.categories.length - 1 && this.catalogItems.filter(item => item.category_id === nextCategory.id)[0]) {
                    this.views.category = nextCategory.id
                    this.selected.catalogItems[nextCategory.slug][0].id = this.catalogItems.filter(item => item.category_id === nextCategory.id)[0].id
                } else {
                    this.$parent.views.step = 'quantity'
                }
            },
        },
    }
</script>