<template>
    <div>
        <h1 class="h3 m-0">Новый расчет</h1>

        {{ inputLines }}

        <div v-for="category in categories" :key="'category_' + category.id">
            {{ category.name }}
            <button @click="addLine(category.slug)">+</button>
            <div v-for="(line, index) in inputLines[category.slug]" :key="index">
                <select v-model="line.value" class="form-select">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
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
                }))
            },
            addLine (slug) {
                let checkEmptyLines = this.inputLines[slug].filter(line => line.value === null)
                if (checkEmptyLines.length >= 1 && this.inputLines[slug].length > 0) {
                    return
                }
                this.inputLines[slug].push({
                    value: null
                })
            },
        },
    }
</script>