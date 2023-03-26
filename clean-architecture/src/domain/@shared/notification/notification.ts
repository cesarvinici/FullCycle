export type NotificationErrorProps = {
    message: string;
    context: string;
}

export default class Notification {
    private _errors: NotificationErrorProps[] = [];

    addError(error: NotificationErrorProps) {
        this._errors.push(error);
    }

    hasErrors(): boolean {
        return this._errors.length > 0;
    }

    errors() {
        return this._errors;
    }

    messages(context?: string): string {
        let messages = "";
        this._errors.forEach(error => {
            if (! context || error.context === context) {
                messages += `${error.context}: ${error.message}, `;
            }
        });
        return messages;
    }
}