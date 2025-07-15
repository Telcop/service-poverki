document.addEventListener('turbo:load', function () {
    const allowedPaths = [
        '/admin/verification/poverki',
        '/admin/verification/uploaded'
    ];
    const currentPath = window.location.pathname;
    console.log(allowedPaths);

    if (allowedPaths.includes(currentPath)) {
        // Вставляем checkbox "выделить все" во первую колонку
        const firstHeaderTh = document.querySelector('thead tr th'); // первая th в строке thead
        if (firstHeaderTh) {
            const targetDiv = firstHeaderTh.querySelector('div'); // ищем внутри первого th
            if (targetDiv) {
                targetDiv.innerHTML = `
                    <input type="checkbox" class="form-check-input" value="0" novalue="0" yesvalue="1" name="selectAll" placeholder="Выделить все" id="select-all">
                    <label class="form-check-label" for="select-all"></label>
                `;
            }
        }

        const button = document.getElementById('date-button'); // Кнопка
        const selectAll = document.getElementById('select-all'); // Выделить все
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="working[]"]'); // Чекбоксы записей
        
        // Функция проверки состояния чекбоксов
        function toggleButtonState() {
            const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            button.disabled = !isAnyChecked; // Активируем/деактивируем кнопку
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

    }
});

document.addEventListener('DOMContentLoaded', function () {
    const allowedPaths = [
        '/admin/verification/poverki',
        '/admin/verification/uploaded'
    ];
    const currentPath = window.location.pathname;
    console.log(allowedPaths);

    if (allowedPaths.includes(currentPath)) {
        // Вставляем checkbox "выделить все" во первую колонку
        const firstHeaderTh = document.querySelector('thead tr th'); // первая th в строке thead
        if (firstHeaderTh) {
            const targetDiv = firstHeaderTh.querySelector('div'); // ищем внутри первого th
            if (targetDiv) {
                targetDiv.innerHTML = `
                    <input type="checkbox" class="form-check-input" value="0" novalue="0" yesvalue="1" name="selectAll" placeholder="Выделить все" id="select-all">
                    <label class="form-check-label" for="select-all"></label>
                `;
            }
        }

        const button = document.getElementById('date-button'); // Кнопка
        const selectAll = document.getElementById('select-all'); // Выделить все
        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="working[]"]'); // Чекбоксы записей
        
        // Функция проверки состояния чекбоксов
        function toggleButtonState() {
            const isAnyChecked = Array.from(checkboxes).some(checkbox => checkbox.checked);
            button.disabled = !isAnyChecked; // Активируем/деактивируем кнопку
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

    }
});