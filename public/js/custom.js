document.addEventListener('turbo:load', function () {
    const button = document.getElementById('date-button'); // Кнопка
    const input = document.getElementById('date-input'); // Поле ввода
    const selectAll = document.getElementById('select-all'); // Выделить все
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="working[]"]'); // Чекбоксы записей

    // Функция проверки состояния чекбоксов
    function toggleButtonState() {
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        button.disabled = !isAnyChecked; // Активируем/деактивируем кнопку
        input.disabled = !isAnyChecked; // Активируем/деактивируем поле ввода
        console.log(button.disabled);
        console.log(input.disabled);
    }

    // Добавляем обработчик события для всех чекбоксов
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleButtonState);
    });

    // Обработчик события для чекбокса "выделить все"
    selectAll.addEventListener('change', function() {
        const isChecked = selectAll.checked;
        
        // Устанавливаем состояние всех чекбоксов в соответствии с состоянием чекбокса "выделить все"
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });

        // Обновляем состояние кнопки и поля ввода
        toggleButtonState();
    });
    

    // Изначально проверяем состояние кнопки
    toggleButtonState();
});

document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('date-button'); // Кнопка
    const input = document.getElementById('date-input'); // Поле ввода
    const selectAll = document.getElementById('select-all'); // Выделить все
    const checkboxes = document.querySelectorAll('input[type="checkbox"][name="working[]"]'); // Чекбоксы записей

    // Функция проверки состояния чекбоксов
    function toggleButtonState() {
        const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
        button.disabled = !isAnyChecked; // Активируем/деактивируем кнопку
        input.disabled = !isAnyChecked; // Активируем/деактивируем поле ввода
        console.log(button.disabled); 
        console.log(input.disabled);
    }

    // Добавляем обработчик события для всех чекбоксов
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', toggleButtonState);
    });

    // Обработчик события для чекбокса "выделить все"
    selectAll.addEventListener('change', function() {
        const isChecked = selectAll.checked;
        
        // Устанавливаем состояние всех чекбоксов в соответствии с состоянием чекбокса "выделить все"
        checkboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
        });

        // Обновляем состояние кнопки и поля ввода
        toggleButtonState();
    });
    
    // Изначально проверяем состояние кнопки
    toggleButtonState();
});