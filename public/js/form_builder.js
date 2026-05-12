/**
 * JavaScript для динамического добавления/удаления полей формы в админке
 */

document.addEventListener('DOMContentLoaded', function() {
    // Добавление нового поля
    const addFieldButton = document.getElementById('add-field');
    if (addFieldButton) {
        addFieldButton.addEventListener('click', function() {
            addFormField();
        });
    }

    // Обработка удаления поля
    document.addEventListener('click', function(e) {
        if (e.target && e.target.classList.contains('remove-field')) {
            removeFormField(e.target);
        }
    });

    // Инициализация обработчиков для существующих полей
    initRemoveButtons();

    // Обновление видимости опций в зависимости от типа поля
    document.addEventListener('change', function(e) {
        if (e.target && e.target.classList.contains('field-type-select')) {
            updateFieldOptions(e.target.closest('.form-structure-field'));
        }
    });

    // Инициализация видимости для всех полей
    document.querySelectorAll('.form-structure-field').forEach(function(field) {
        updateFieldOptions(field);
    });
});

/**
 * Добавление нового поля формы
 */
function addFormField() {
    const container = document.getElementById('form-structure-fields');
    const prototype = container.getAttribute('data-prototype');
    const index = container.children.length;

    // Заменяем __name__ на индекс
    const newForm = prototype.replace(/__name__/g, index);

    // Создаем обертку для нового поля
    const wrapper = document.createElement('div');
    wrapper.innerHTML = '<div class="form-structure-field card mb-3">' +
                       '    <div class="card-header d-flex justify-content-between align-items-center">' +
                       '        <span>Поле</span>' +
                       '        <button type="button" class="btn btn-danger btn-sm remove-field">' +
                       '            <i class="fas fa-times"></i> Удалить' +
                       '        </button>' +
                       '    </div>' +
                       '    <div class="card-body">' +
                       '        ' + newForm +
                       '    </div>' +
                       '</div>';

    container.appendChild(wrapper.firstElementChild);

    // Инициализируем обработчик удаления для нового поля
    const removeButton = wrapper.querySelector('.remove-field');
    if (removeButton) {
        removeButton.addEventListener('click', function() {
            removeFormField(this);
        });
    }

    // Инициализируем видимость опций для нового поля
    updateFieldOptions(wrapper.firstElementChild);
}

/**
 * Удаление поля формы
 */
function removeFormField(button) {
    if (confirm('Вы уверены, что хотите удалить это поле?')) {
        const field = button.closest('.form-structure-field');
        if (field) {
            field.remove();
        }
    }
}

/**
 * Инициализация кнопок удаления для существующих полей
 */
function initRemoveButtons() {
    document.querySelectorAll('.remove-field').forEach(function(button) {
        button.addEventListener('click', function() {
            removeFormField(this);
        });
    });
}

/**
 * Обновление видимости опций в зависимости от типа поля
 */
function updateFieldOptions(fieldElement) {
    if (!fieldElement) return;

    const typeSelect = fieldElement.querySelector('.field-type-select');
    if (!typeSelect) return;

    const type = typeSelect.value;
    
    // Находим все группы опций
    const optionGroups = fieldElement.querySelectorAll('.form-group, .form-check');
    
    optionGroups.forEach(function(group) {
        const label = group.querySelector('label');
        if (!label) return;
        
        const labelText = label.textContent.trim().toLowerCase();
        
        // Скрываем/показываем группы в зависимости от типа поля
        let show = false;
        
        switch(type) {
            case 'text':
                show = labelText === 'placeholder';
                break;
            case 'select':
                show = labelText === 'варианты (для select)';
                break;
            case 'file':
                show = labelText === 'множественная загрузка' || 
                       labelText === 'максимальное количество файлов' || 
                       labelText === 'разрешённые расширения';
                break;
            default:
                show = false;
        }
        
        group.style.display = show ? 'block' : 'none';
    });
}