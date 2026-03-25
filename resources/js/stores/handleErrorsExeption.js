import { ElNotification } from 'element-plus';

export const handleErrorsExeption = (e) => {
    const type = Object.prototype.toString.call(e);
    let message = "";

    if (type === '[object Object]') {
        if (e.response.data.errors) {
            for (let i in e.response.data.errors) {
                e.response.data.errors[i].forEach(msg => {
                    message += `<div style="margin-bottom: 6px;">• ${msg}</div>`;
                });
            }
        }
        else if (e.response.data.error) {
            message = e.response.data.error;
        }
        else {
            message = 'Hubo un error desconocido';
        }

        ElNotification({
            message: message,
            type: 'error',
            dangerouslyUseHTMLString: true
        });
    }
    else {
        ElNotification({
            message: 'Hubo un error en el recurso: ' + e,
            type: 'error',
            dangerouslyUseHTMLString: true
        });
    }
}