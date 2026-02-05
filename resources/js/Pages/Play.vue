<script setup>
import { ref, onMounted, computed, watch } from 'vue'

const props = defineProps({
    puzzle: Object
})

// Grid & clue
const grid = ref([])
const words = ref([])
const userGrid = ref([])
const activeCell = ref({ row: 0, col: 0 })

// Cek apakah permainan selesai
const isFinished = computed(() => {
    for (let r = 0; r < grid.value.length; r++) {
        for (let c = 0; c < grid.value[r].length; c++) {
            if (grid.value[r][c]) {
                if (!userGrid.value[r][c]) return false
                if (userGrid.value[r][c].toUpperCase() !== grid.value[r][c]) {
                    return false
                }
            }
        }
    }
    return true
})

// Redirect ke dashboard jika selesai
watch(isFinished, val => {
    if (val) {
        setTimeout(() => {
            window.history.back()
        }, 2000)
    }
})

// Inisialisasi grid & clue
onMounted(() => {
    if (!props.puzzle || !props.puzzle.grid || !props.puzzle.words) return

    try {
        grid.value = JSON.parse(props.puzzle.grid)
        words.value = JSON.parse(props.puzzle.words)
    } catch (err) {
        console.error('Error parsing puzzle data', err)
        return
    }

    initializeUserGrid()
})

// Inisialisasi grid user (input kosong)
function initializeUserGrid() {
    userGrid.value = grid.value.map(row =>
        row.map(cell => (cell ? '' : null))
    )
}

// Dapatkan nomor sel untuk clue
function getCellNumber(r, c) {
    if (!grid.value[r][c]) return null

    const isStartAcross =
        (c === 0 || !grid.value[r][c - 1]) &&
        c + 1 < grid.value[r].length &&
        grid.value[r][c + 1]

    const isStartDown =
        (r === 0 || !grid.value[r - 1][c]) &&
        r + 1 < grid.value.length &&
        grid.value[r + 1][c]

    if (isStartAcross || isStartDown) {
        let count = 0
        for (let i = 0; i <= r; i++) {
            for (let j = 0; j < grid.value[i].length; j++) {
                const startAcross =
                    grid.value[i][j] &&
                    (j === 0 || !grid.value[i][j - 1]) &&
                    j + 1 < grid.value[i].length &&
                    grid.value[i][j + 1]

                const startDown =
                    grid.value[i][j] &&
                    (i === 0 || !grid.value[i - 1][j]) &&
                    i + 1 < grid.value.length &&
                    grid.value[i + 1][j]

                if (startAcross || startDown) count++
                if (i === r && j === c) return count
            }
        }
    }

    return null
}

// Keyboard navigation
function handleKeyDown(e, r, c) {
    const key = e.key
    if (key === 'ArrowRight') moveToCell(r, c + 1)
    if (key === 'ArrowLeft') moveToCell(r, c - 1)
    if (key === 'ArrowDown') moveToCell(r + 1, c)
    if (key === 'ArrowUp') moveToCell(r - 1, c)
    if (key === 'Tab') {
        e.preventDefault()
        moveToNextCell(r, c)
    }
    activeCell.value = { row: r, col: c }
}

function moveToCell(r, c) {
    if (!grid.value[r] || !grid.value[r][c]) return
    focusCell(r, c)
}

function moveToNextCell(r, c) {
    let newR = r
    let newC = c + 1

    while (newR < grid.value.length) {
        while (newC < grid.value[newR].length) {
            if (grid.value[newR][newC]) {
                focusCell(newR, newC)
                return
            }
            newC++
        }
        newR++
        newC = 0
    }
    focusCell(0, 0)
}

function focusCell(r, c) {
    const input = document.querySelector(`[data-row="${r}"][data-col="${c}"]`)
    if (input) {
        input.focus()
        input.select()
    }
    activeCell.value = { row: r, col: c }
}

function handleCellClick(r, c) {
    if (grid.value[r][c]) focusCell(r, c)
}

function handleClueClick(word) {
    if (word.row !== undefined && word.col !== undefined) {
        focusCell(word.row, word.col)
    }
}

function getWordNumber(word) {
    return getCellNumber(word.row, word.col)
}

function goBack() {
    window.history.back()
}

// Computed property to track correctly filled cells
const correctCells = computed(() => {
    const cellSet = new Set()
    if (!words.value || !userGrid.value.length) return cellSet

    words.value.forEach(wordObj => {
        let isWordCorrect = true
        for (let i = 0; i < wordObj.word.length; i++) {
            const r = wordObj.direction === 'down' ? wordObj.row + i : wordObj.row
            const c =
                wordObj.direction === 'across' ? wordObj.col + i : wordObj.col

            if (
                !userGrid.value[r] ||
                !userGrid.value[r][c] ||
                userGrid.value[r][c].toUpperCase() !== wordObj.word[i]
            ) {
                isWordCorrect = false
                break
            }
        }

        if (isWordCorrect) {
            for (let i = 0; i < wordObj.word.length; i++) {
                const r =
                    wordObj.direction === 'down' ? wordObj.row + i : wordObj.row
                const c =
                    wordObj.direction === 'across'
                        ? wordObj.col + i
                        : wordObj.col
                cellSet.add(`${r},${c}`)
            }
        }
    })
    return cellSet
})

// Filter and sort clues
const acrossWords = computed(() => {
    if (!words.value || words.value.length === 0) return []
    return words.value
        .filter(w => w.direction === 'across')
        .sort((a, b) => getWordNumber(a) - getWordNumber(b))
})

const downWords = computed(() => {
    if (!words.value || words.value.length === 0) return []
    return words.value
        .filter(w => w.direction === 'down')
        .sort((a, b) => getWordNumber(a) - getWordNumber(b))
})
</script>

<template>
    <div class="puzzle-container">
        <!-- Header -->
        <button @click="goBack" class="back-btn">
            ← Kembali
        </button>

        <h1 class="puzzle-title">Teka-Teki Silang</h1>

        <div v-if="isFinished" class="success-message">
            🎉 Semua jawaban benar!
        </div>

        <div class="puzzle-content">
            <!-- GRID -->
            <div class="puzzle-grid-section">
                <div class="puzzle-grid" :style="{
                    gridTemplateColumns: `repeat(${grid[0]?.length || 10}, 40px)`
                }">
                    <template v-for="(row, r) in grid" :key="r">
                        <template v-for="(cell, c) in row" :key="c">
                            <div v-if="cell" class="grid-cell">
                                <span v-if="getCellNumber(r, c)" class="cell-number">
                                    {{ getCellNumber(r, c) }}
                                </span>
                                <input v-model="userGrid[r][c]" :data-row="r" :data-col="c" maxlength="1"
                                    @keydown="handleKeyDown($event, r, c)" @click="handleCellClick(r, c)"
                                    :class="{ 'correct-word': correctCells.has(`${r},${c}`) }" />
                            </div>
                            <div v-else class="grid-block"></div>
                        </template>
                    </template>
                </div>
            </div>

            <!-- CLUES -->
            <div class="clues-section">
                <h3 class="clues-title">Clue</h3>

                <div class="clues-group">
                    <h4 class="clues-subtitle">Mendatar</h4>
                    <div v-for="(word, i) in acrossWords" :key="i" @click="handleClueClick(word)" class="clue-item">
                        {{ getWordNumber(word) }}. {{ word.clue }}
                    </div>
                </div>

                <div class="clues-group">
                    <h4 class="clues-subtitle">Menurun</h4>
                    <div v-for="(word, i) in downWords" :key="i" @click="handleClueClick(word)" class="clue-item">
                        {{ getWordNumber(word) }}. {{ word.clue }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.puzzle-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 24px;
}

.back-btn {
    background: #4a5568;
    color: white;
    padding: 8px 20px;
    border: none;
    border-radius: 6px;
    font-weight: 500;
    font-size: 14px;
    cursor: pointer;
    margin-bottom: 20px;
    text-decoration: none;
    display: inline-block;
}

.back-btn:hover {
    background: #2d3748;
}

.puzzle-title {
    font-size: 24px;
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 20px;
    text-align: center;
}

.success-message {
    background: #48bb78;
    color: white;
    padding: 14px 20px;
    border-radius: 8px;
    margin-bottom: 24px;
    font-size: 14px;
    font-weight: 500;
    text-align: center;
}

.puzzle-content {
    display: flex;
    gap: 40px;
    align-items: flex-start;
}

.puzzle-grid-section {
    background: white;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
}

.puzzle-grid {
    display: grid;
    gap: 2px;
    background: #4a5568;
    padding: 2px;
    border-radius: 4px;
}

.grid-cell {
    position: relative;
    width: 40px;
    height: 40px;
    background: white;
}

.grid-cell input {
    width: 100%;
    height: 100%;
    text-align: center;
    font-weight: bold;
    font-size: 16px;
    text-transform: uppercase;
    border: 1px solid #e2e8f0;
    background: white;
    color: #2d3748;
    caret-color: transparent;
}

.grid-cell input:focus {
    outline: none;
    border-color: #4299e1;
    background: #f7fafc;
}

.grid-cell input.correct-word {
    background-color: #c6f6d5;
    color: #2f855a;
    font-weight: bold;
}

.grid-block {
    width: 40px;
    height: 40px;
    background: #2d3748;
}

.cell-number {
    position: absolute;
    top: 2px;
    left: 3px;
    font-size: 10px;
    color: #718096;
    font-weight: 500;
}

.clues-section {
    background: white;
    padding: 24px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
    min-width: 280px;
}

.clues-title {
    font-size: 18px;
    color: #2d3748;
    font-weight: 600;
    margin-bottom: 20px;
}

.clues-group {
    margin-bottom: 24px;
}

.clues-subtitle {
    font-size: 14px;
    color: #4a5568;
    font-weight: 600;
    margin-bottom: 12px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.clue-item {
    font-size: 14px;
    color: #718096;
    margin-bottom: 8px;
    padding: 6px 0;
    cursor: pointer;
    border-bottom: 1px solid #e2e8f0;
    transition: color 0.2s;
}

.clue-item:hover {
    color: #4299e1;
}

.clue-item:last-child {
    border-bottom: none;
}

/* Responsive */
@media (max-width: 900px) {
    .puzzle-content {
        flex-direction: column;
        gap: 24px;
    }

    .clues-section {
        width: 100%;
    }

    .puzzle-grid {
        grid-template-columns: repeat(15, 32px) !important;
    }

    .grid-cell,
    .grid-block {
        width: 32px;
        height: 32px;
    }

    .grid-cell input {
        font-size: 14px;
    }
}

@media (max-width: 480px) {
    .puzzle-container {
        padding: 16px;
    }

    .puzzle-grid-section,
    .clues-section {
        padding: 20px;
    }

    .puzzle-grid {
        grid-template-columns: repeat(15, 28px) !important;
    }

    .grid-cell,
    .grid-block {
        width: 28px;
        height: 28px;
    }
}
</style>
