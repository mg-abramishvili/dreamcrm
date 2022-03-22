<template>
    <div class="mb-4">
        <loader v-if="views.loading"></loader>

        <div v-else v-for="(category, index) in categories" :key="category.id">
            <div v-show="views.category == category.id">
                <div class="calculation-left-block-main-label">
                    <strong>{{ category.name }}</strong>
                    <button @click="addCatalogItem(category.slug)" class="btn btn-sm btn-outline-danger">+</button>
                </div>

                <div v-for="(catalogItem, index) in selected.catalogItems[category.slug]" :key="index" style="position: relative;">
                    <select v-model="catalogItem.id" class="form-select form-select-lg mt-2 mb-3">
                        <option v-for="catalogItem in category.items" :key="catalogItem.id" :value="catalogItem.id">
                            {{ catalogItem.name }}
                            <template v-if="catalogItem.price > 0">
                                &mdash; {{ catalogItem.price | currency }} ₽
                            </template>
                        </option>
                    </select>
                    <button v-if="index > 0 && selected.catalogItems[category.slug].length > 1" @click="deleteCatalogItem(catalogItem.id, category.slug)" class="btn btn-sm btn-outline-danger" style="position: absolute; right: 0; top: 50%; transform: translateY(-50%); padding: 0; width: 20px; margin-right: -21px;">&ndash;</button>
                </div>

                <div class="mt-4">
                    <button @click="prevCategory(category, index)" class="btn btn-outline-primary">Назад</button>
                    <button @click="nextCategory(category, index)" class="btn btn-outline-primary">Далее</button>
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
                }
            },
            selected: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.selected.catalogItems)) {                        
                        if(category[1].length) {
                            let selectedItems = this.categories.find(c => c.slug == category[0]).items
                            
                            category[1].forEach((i) => {
                                if(i.id != null) {
                                    i.price = selectedItems.find(item => item.id == i.id).price
                                    i.pre_rub = selectedItems.find(item => item.id == i.id).pre_rub
                                    i.pre_usd = selectedItems.find(item => item.id == i.id).pre_usd
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
                if(!this.box_id) {
                    return
                }

                axios
                .get(`/api/catalog/${this.box_id}/categories`)
                .then((response => {
                    this.categories = response.data
                    this.$parent.categories = response.data

                    response.data.forEach(category => {
                        this.$set(this.selected.catalogItems, category.slug, [])
                        this.addCatalogItem(category.slug)
                    })

                    this.firstCategory()
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
            prevCategory(category, index) {
                let prevCategory = this.categories[index - 1]

                if(prevCategory) {
                    this.views.category = prevCategory.id
                } else {
                    this.$parent.views.step = 'box'
                }
            },
            nextCategory(category, index) {
                let nextCategory = this.categories[index + 1]

                if(nextCategory && nextCategory.items.length) {
                    this.views.category = nextCategory.id

                    if(!this.selected.catalogItems[nextCategory.slug][0].id) {
                        this.selected.catalogItems[nextCategory.slug][0].id = nextCategory.items.filter(item => item.category_id == nextCategory.id)[0].id
                    }
                } else {
                    this.$parent.views.step = 'quantity'
                }
            },
            firstCategory() {
                let firstCategory = this.categories[0]

                this.views.category = firstCategory.id
                this.selected.catalogItems[firstCategory.slug][0].id = firstCategory.items.filter(item => item.category_id == firstCategory.id)[0].id
            },
        },
    }
</script>