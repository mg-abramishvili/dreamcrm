<template>
    <div>
        <h1 class="h3 m-0">Новый расчет</h1>
        
        <div class="mb-4">
            Корпус
            <select v-model="inputBox" class="form-select">
                <option v-for="box in boxes" :key="'box_' + box.id" :value="box.id">{{ box.name }}</option>
            </select>
        </div>

        <div v-for="category in categories" :key="'category_' + category.id">
            <div v-show="currentCategory == category.id">
                {{ category.name }}
                <button @click="addLine(category.slug)">+</button>
                <div v-for="(line, index) in inputLines[category.slug]" :key="index">
                    <select v-model="line.value" class="form-select">
                        <template v-for="element in elementsFiltered">
                            <option v-if="element.category_id == category.id" :value="element.id">
                                {{ element.name }} <template v-if="element.price > 0">- {{ element.price}}</template>
                            </option>
                        </template>
                    </select>
                </div>
                <button @click="prevCategory(category)">Назад</button>
                <button @click="nextCategory(category)">Далее</button>
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
                inputLines: {},

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
            }
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
                        this.$set(this.inputLines, category.slug, [])
                        this.addLine(category.slug)
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
            addLine(slug) {
                let checkEmptyLines = this.inputLines[slug].filter(line => line.value === null)
                if (checkEmptyLines.length >= 1 && this.inputLines[slug].length > 0) {
                    return
                }
                this.inputLines[slug].push({
                    value: null
                })
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
                        this.inputLines[this.categories[index + 1].slug][0].value = this.elementsFiltered.filter(element => element.category_id == this.categories[index + 1].id)[0].id
                    }
                }
            },
        },
    }
</script>