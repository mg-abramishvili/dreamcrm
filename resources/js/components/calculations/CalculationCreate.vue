<template>
    <div>
        <h1 class="h3 m-0">Новый расчет</h1>

        <div v-for="category in categories" :key="'category_' + category.id">
            <div v-show="currentCategory == category.id">
                {{ category.name }}
                <button @click="addLine(category.slug)">+</button>
                <div v-for="(line, index) in inputLines[category.slug]" :key="index">
                    <select v-model="line.value" class="form-select">
                        <option v-for="element in category.elements" :key="'element_' + element.id" :value="element.id">{{ element.name }}</option>
                    </select>
                </div>
                <button @click="nextCategory(category)">Далее</button>
            </div>
        </div>
        
    </div>
</template>

<script>
    export default {
        data() {
            return {
                elements: [],
                boxes: [],
                categories: [],

                inputLines: {},

                currentCategory: '',
            }
        },
        created() {
            this.loadCategories()
        },
        mounted () {
        },
        methods: {
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
            addLine(slug) {
                let checkEmptyLines = this.inputLines[slug].filter(line => line.value === null)
                if (checkEmptyLines.length >= 1 && this.inputLines[slug].length > 0) {
                    return
                }
                this.inputLines[slug].push({
                    value: null
                })
            },
            nextCategory(category) {
                var index = this.categories.indexOf(category)
                if(index >= 0 && index < this.categories.length - 1) {
                    this.currentCategory = this.categories[index + 1].id
                }

                
            },
        },
    }
</script>