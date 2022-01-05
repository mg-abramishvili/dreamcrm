<template>
    <div>
        <h1 class="h3 m-0">Новый расчет</h1>
        
        <div class="row">
            <div class="col-12 col-lg-4">
                <div class="calculation-input" style="position: sticky; top: 20px;">
                    <div class="mb-4">
                        Корпус
                        <select v-model="inputBox" class="form-select">
                            <option v-for="box in boxes" :key="'box_' + box.id" :value="box.id">{{ box.name }}</option>
                        </select>
                    </div>

                    <div v-for="category in categories" :key="'category_' + category.id">
                        <div v-show="currentCategory == category.id">
                            {{ category.name }}

                            <button @click="addElement(category.slug)">+</button>
                            
                            <div v-for="(element, index) in inputElements[category.slug]" :key="index">
                                <select v-model="element.id" class="form-select">
                                    <template v-for="element in elementsFiltered">
                                        <option v-if="element.category_id == category.id" :value="element.id">
                                            {{ element.name }} <template v-if="element.price > 0">- {{ element.price}}</template>
                                        </option>
                                    </template>
                                </select>
                                <button v-if="index > 0 && inputElements[category.slug].length > 1" @click="deleteElement(element.id, category.slug)">-</button>
                            </div>

                            <button @click="prevCategory(category)">Назад</button>
                            <button @click="nextCategory(category)">Далее</button>
                        </div>
                    </div>
                    
                    {{ price }}
                </div>
            </div>
            <div class="col-12 col-lg-8">
                <!-- <ul>
                    <li v-for="(element, categorySlug) in inputElements" :key="categorySlug"><strong>{{ categorySlug }}:</strong> {{ element }}</li>
                </ul> -->
                <div v-for="category in categories" :key="'category_' + category.id" class="mb-3 bg-white px-3 py-3">
                    <small style="color: rgb(136, 136, 136);">{{ category.name }}</small>
                    <template v-for="element in elementsFiltered">
                        <template v-if="element.category_id == category.id">
                            <template v-for="inEl in inputElements">
                                <template v-for="inElEl in inEl">
                                    <div v-if="element.id == inElEl.id" class="row align-items-center">
                                        <div class="col-8">
                                            <strong class="d-block">{{ element.name }}</strong>
                                        </div>
                                        <div class="col-4 text-end">
                                            <strong class="text-primary">{{ element.price }}</strong>
                                        </div>
                                    </div>
                                </template>
                            </template>
                        </template>
                    </template>
                </div>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                boxes: [],
                categories: [],
                elements: [],

                inputBox: {},
                inputElements: {},

                currentCategory: '',
            }
        },
        created() {
            this.loadBoxes()
            this.loadCategories()
            this.loadElements()
        },
        mounted () {
        },
        watch: {
            inputElements: {
                deep: true,
                handler() {
                    for (const category of Object.entries(this.inputElements)) {
                        if(category[1] && category[1].length > 0) {
                            category[1].forEach((el) => {
                                if(el.id != null) {
                                    el.price = parseInt(this.elements.filter(element => element.id == el.id)[0].price)
                                }
                            })
                        }
                    }
                }
            }
        },
        computed: {
            elementsFiltered() {
                if(this.inputBox && this.inputBox > 0) {
                    var array = []
                    this.elements.forEach((element) => {
                        if(element.boxes && element.boxes.length > 0) {
                            element.boxes.forEach((box) => {
                                if(box.id == this.inputBox) {
                                    array.push(element)
                                }
                            })
                        }
                    })
                    return array
                } else {
                    return this.elements
                }
            },
            price() {
                var price = []
                for (const category of Object.entries(this.inputElements)) {
                    if(category[1] && category[1].length > 0) {
                        category[1].forEach((el) => {
                            price.push(el.price)
                        })
                    }
                }
                return price.reduce((a, b) => a + b, 0)
            },
        },
        methods: {
            loadBoxes() {
                axios
                .get('/api/boxes')
                .then((response => {
                    this.boxes = response.data
                }))
            },
            loadCategories() {
                axios
                .get('/api/categories')
                .then((response => {
                    this.categories = response.data

                    response.data.forEach(category => {
                        this.$set(this.inputElements, category.slug, [])
                        this.addElement(category.slug)
                    })

                    this.currentCategory = response.data[0].id
                }))
            },
            loadElements() {
                axios
                .get('/api/elements')
                .then((response => {
                    this.elements = response.data
                }))
            },
            addElement(categorySlug) {
                let checkEmpty = this.inputElements[categorySlug].filter(element => element.id === null)
                if (checkEmpty.length >= 1 && this.inputElements[categorySlug].length > 0) {
                    return
                }
                this.inputElements[categorySlug].push({id: null, price: 0})
            },
            deleteElement(ElementID, categorySlug) {
                var index = this.inputElements[categorySlug].map(element => {
                    return element.id;
                }).indexOf(ElementID);
                this.inputElements[categorySlug].splice(index, 1)
            },
            prevCategory(category) {
                var index = this.categories.indexOf(category)
                if(index > 0 && index < this.categories.length + 1) {
                    this.currentCategory = this.categories[index - 1].id
                }
            },
            nextCategory(category) {
                var index = this.categories.indexOf(category)
                if(index >= 0 && index < this.categories.length - 1) {
                    this.currentCategory = this.categories[index + 1].id

                    if(this.categories[index + 1].elements && this.categories[index + 1].elements.length > 0) {
                        this.inputElements[this.categories[index + 1].slug][0].id = this.elementsFiltered.filter(element => element.category_id == this.categories[index + 1].id)[0].id
                    }
                }
            },
        },
    }
</script>