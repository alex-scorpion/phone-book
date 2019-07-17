import template from './vue-toastr.html';
import toast from './toast/toast.js';

export default {
    template: template,
    name: 'vueToastr',
    data() {
        let positions = [
            'toast-top-right',
            'toast-bottom-right',
            'toast-bottom-left',
            'toast-top-left',
            'toast-top-full-width',
            'toast-bottom-full-width',
            'toast-top-center',
            'toast-bottom-center'
        ];
        let codes = {
            0:   "Неизвесная ошибка",
            100: "Продолжай",
            101: "Переключение протоколов",
            102: "Идёт обработка",
            200: "Ok",
            201: "Созданно",
            202: "Принято",
            203: "Информация не авторитетна",
            204: "Нет содержимого",
            205: "Сбросить содержимое",
            206: "Частичное содержимое",
            207: "Многостатусный",
            226: "Использовано IM",
            300: "Множество выборов",
            302: "Найдено",
            303: "Смотреть другое",
            304: "Не изменялось",
            305: "Использовать прокси",
            307: "Временное перенаправление",
            400: "Неверный запрос",
            401: "Не авторизован",
            402: "Необходима оплата",
            403: "Запрещенно",
            404: "Метод не найден",
            405: "Метод не поддерживается",
            406: "Неприемлемо",
            407: "Необходима аутентификация прокси",
            408: "Истекло время ожидания",
            409: "Конфликт",
            410: "Удалён",
            411: "Необходима длина",
            412: "Условие ложно",
            413: "Размер запроса слишком велик",
            414: "Запрашиваемый URI слишком длинный",
            415: "Неподдерживаемый тип данных",
            416: "Запрашиваемый диапазон не достижим",
            417: "Ожидаемое неприемлемо",
            422: "Ошибка валидации",
            423: "Превышенно количество запросов к серверу",
            424: "Невыполненная зависимость",
            425: "Неупорядоченный набор",
            426: "Необходимо обновление",
            428: "Необходимо предусловие",
            429: "Слишком много запросов",
            431: "Поля заголовка запроса слишком большие",
            451: "Недоступно по юридическим причинам",
            500: "Ошибка сервера",
            501: "Не реализовано",
            502: "Ошибочный шлюз",
            503: "Сервис недоступен",
            504: "Шлюз не отвечает",
            505: "Версия HTTP не поддерживается",
            506: "Вариант тоже проводит согласование",
            507: "Переполнение хранилища",
            508: "Обнаружено бесконечное перенаправление",
            509: "Исчерпана пропускная ширина канала",
            510: "Не расширено",
            511: "Требуется сетевая аутентификация",
            553: "Ошибка отправки почты",
        };
        let list = {};
        for (let i = 0; i <= positions.length - 1; i++) {
            list[positions[i]] = {};
        }

        return {
            positions,
            codes,
            defaultPosition: 'toast-top-right',
            defaultType: 'success',
            defaultCloseOnHover: true,
            defaultTimeout: 5000,
            defaultProgressBar: false,
            defaultProgressBarValue: null,
            defaultPreventDuplicates: false,
            list,
            index: 0,
            defaultStyle: {}
        }
    },
    components: {
        toast
    },
    methods: {
        addToast (data) {
            this.index++;
            data['index'] = this.index;
            this.$set(this.list[data.position], this.index, data);
            // if have onCreated
            if (typeof data.onCreated !== 'undefined') {
                // wait doom update after call cb
                this.$nextTick(() => {
                    data.onCreated();
                })
            }
        },
        removeToast (data) {
            let item = this.list[data.position][data.index];
            // console.log("remove toast", data, item);
            if (typeof item !== 'undefined') {
                this.$delete(this.list[data.position], data.index);
                // if have onClosed
                if (typeof data.onClosed !== 'undefined') {
                    // wait doom update after call cb
                    this.$nextTick(() => {
                        data.onClosed();
                    })
                }
            }
        },
        setProgress (data, newValue) {
            let item = this.list[data.position][data.index];
            if (typeof item !== 'undefined') {
                this.$set(item, 'progressBarValue', newValue);
            }
        },
        Add (d) {
            return this.AddData(this.processObjectData(d));
        },
        AddData (data) {
            if (typeof data !== 'object') {
                console.log('AddData accept only Object', data);
                return false;
            }
            if (data.preventDuplicates) {
                let listKeys = Object.keys(this.list[data.position]);
                for (let i = 0; i < listKeys.length; i++) {
                    if (this.list[data.position][listKeys[i]].title === data.title && this.list[data.position][listKeys[i]].msg === data.msg) {
                        console.log('Prevent Duplicates', data);
                        return false;
                    }
                }
            }
            this.addToast(data);
            return data;
        },
        processObjectData (data) {
            // if Object
            if (typeof data === 'object' && typeof data.msg !== 'undefined') {
                if (typeof data.position === 'undefined') {
                    data.position = this.defaultPosition;
                }
                if (typeof data.type === 'undefined') {
                    data.type = this.defaultType;
                }
                if (typeof data.timeout === 'undefined') {
                    data.timeout = this.defaultTimeout;
                }
                if (typeof data.progressbar === 'undefined') {
                    data.progressbar = this.defaultProgressBar;
                }
                if (typeof data.progressBarValue === 'undefined') {
                    data.progressBarValue = this.defaultProgressBarValue;
                }
                if (typeof data.closeOnHover === 'undefined') {
                    data.closeOnHover = this.defaultCloseOnHover;
                }
                if (typeof data.preventDuplicates === 'undefined') {
                    data.preventDuplicates = this.defaultPreventDuplicates;
                }
                if (typeof data.style === 'undefined') {
                    data.style = this.defaultStyle;
                }

                return data
            }

            return {
                msg: data.toString(),
                position: this.defaultPosition,
                type: this.defaultType,
                timeout: this.defaultTimeout,
                closeOnHover: this.defaultCloseOnHover,
                progressbar: this.defaultProgressBar,
                progressBarValue: this.defaultProgressBarValue,
                preventDuplicates: this.defaultPreventDuplicates,
            }
        },
        addError (msg, title) {
            let data = this.processObjectData(msg);
            data['type'] = 'error';
            if (typeof title !== 'undefined') {
                data['title'] = title;
            }
            return this.AddData(data);
        },
        addSuccess (msg, title) {
            let data = this.processObjectData(msg);
            data['type'] = 'success';
            if (typeof title !== 'undefined') {
                data['title'] = title;
            }
            return this.AddData(data);
        },
        addWarning (msg, title) {
            let data = this.processObjectData(msg);
            data['type'] = 'warning';
            if (typeof title !== 'undefined') {
                data['title'] = title;
            }
            return this.AddData(data);
        },
        addInfo (msg, title) {
            let data = this.processObjectData(msg);
            data['type'] = 'info';
            if (typeof title !== 'undefined') {
                data['title'] = title;
            }
            return this.AddData(data);
        },
        addCode (code) {
            if (typeof this.codes[code] === "undefined") {
                code = 0;
            }
            let data = this.processObjectData(this.codes[code]);
            data['type']  = 'error';
            data['title'] = `Error ${code}`;
            return this.AddData(data);
        },
        Close (data) {
            this.removeToast(data);
        },
        removeByType (toastType) {
            for (let i = 0; i < this.positions.length; i++) {
                let listKeys = Object.keys(this.list[this.positions[i]]);
                for (let j = 0; j < listKeys.length; j++) {
                    if (this.list[this.positions[i]][listKeys[j]]['type'] === toastType) {
                        this.Close(this.list[this.positions[i]][listKeys[j]]);
                    }
                }
            }
        },
        clearAll () {
            for (let i = 0; i < this.positions.length; i++) {
                let listKeys = Object.keys(this.list[this.positions[i]]);
                for (let j = 0; j < listKeys.length; j++) {
                    this.Close(this.list[this.positions[i]][listKeys[j]]);
                }
            }
        }
    }
}
