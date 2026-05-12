/**
 * JavaScript для обработки загрузки файлов в пользовательской части
 */

document.addEventListener('DOMContentLoaded', function() {
    // Обработка выбора файлов
    const fileInputs = document.querySelectorAll('input[type="file"][name^="files["]');
    
    fileInputs.forEach(function(input) {
        input.addEventListener('change', function(e) {
            handleFileSelection(e.target);
        });
        
        // Инициализация для уже выбранных файлов
        if (input.files.length > 0) {
            handleFileSelection(input);
        }
    });
});

/**
 * Обработка выбора файлов
 */
function handleFileSelection(input) {
    const maxFiles = input.dataset.maxFiles ? parseInt(input.dataset.maxFiles) : 5;
    const allowedExtensions = input.dataset.allowedExtensions ? input.dataset.allowedExtensions.split(',') : [];
    const fileList = input.closest('.file-upload-container').querySelector('.file-list');
    const progressBar = input.closest('.file-upload-container').querySelector('.progress');
    
    // Очистка предыдущего списка
    fileList.innerHTML = '';
    
    // Проверка количества файлов
    if (input.files.length > maxFiles) {
        alert(`Можно загрузить не более ${maxFiles} файлов`);
        input.value = '';
        fileList.innerHTML = '<div class="text-muted">Файлы не выбраны</div>';
        progressBar.classList.add('d-none');
        return;
    }
    
    // Проверка расширений файлов
    for (let i = 0; i < input.files.length; i++) {
        const file = input.files[i];
        const fileExtension = getFileExtension(file.name).toLowerCase();
        
        if (allowedExtensions.length > 0 && !allowedExtensions.includes(fileExtension)) {
            alert(`Файл "${file.name}" имеет недопустимое расширение. Разрешённые форматы: ${allowedExtensions.join(', ')}`);
            input.value = '';
            fileList.innerHTML = '<div class="text-muted">Файлы не выбраны</div>';
            progressBar.classList.add('d-none');
            return;
        }
    }
    
    // Отображение выбранных файлов
    if (input.files.length === 0) {
        fileList.innerHTML = '<div class="text-muted">Файлы не выбраны</div>';
        progressBar.classList.add('d-none');
    } else {
        const ul = document.createElement('ul');
        ul.className = 'list-unstyled';
        
        for (let i = 0; i < input.files.length; i++) {
            const file = input.files[i];
            const li = document.createElement('li');
            li.className = 'mb-1';
            
            const fileSize = formatFileSize(file.size);
            li.innerHTML = `
                <i class="fas fa-paperclip text-muted"></i>
                <span>${file.name}</span>
                <small class="text-muted ml-2">(${fileSize})</small>
            `;
            
            ul.appendChild(li);
        }
        
        fileList.appendChild(ul);
        progressBar.classList.remove('d-none');
        
        // Симуляция прогресса загрузки
        simulateUploadProgress(progressBar);
    }
}

/**
 * Получение расширения файла
 */
function getFileExtension(filename) {
    return filename.slice((filename.lastIndexOf(".") - 1 >>> 0) + 2);
}

/**
 * Форматирование размера файла
 */
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

/**
 * Симуляция прогресса загрузки
 */
function simulateUploadProgress(progressBar) {
    const progressBarInner = progressBar.querySelector('.progress-bar');
    progressBarInner.style.width = '0%';
    
    let width = 0;
    const interval = setInterval(function() {
        if (width >= 100) {
            clearInterval(interval);
        } else {
            width++;
            progressBarInner.style.width = width + '%';
        }
    }, 20);
}