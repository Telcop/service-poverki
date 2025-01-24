document.addEventListener('turbo:load', function () {
    const button = document.getElementById('date-button'); // Кнопка
    const input = document.getElementById('date-input'); // Поле ввода
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

    // Изначально проверяем состояние кнопки
    toggleButtonState();
});

document.addEventListener('DOMContentLoaded', function () {
    const button = document.getElementById('date-button'); // Кнопка
    const input = document.getElementById('date-input'); // Поле ввода
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

    // Изначально проверяем состояние кнопки
    toggleButtonState();
});