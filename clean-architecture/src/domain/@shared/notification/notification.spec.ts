import Notification from "./notification";

describe("Notification unit tests", () => {
    it("Should create a notification error", () => {

        const notification = new Notification();

        const error = {
            message: "Error message",
            context: "customer"
        }

        notification.addError(error);

        expect(notification.messages("customer"))
            .toBe("customer: Error message, ");

        const error2 = {
            message: "Error message 2",
            context: "customer"
        }

        notification.addError(error2);
        expect(notification.messages("customer"))
            .toBe("customer: Error message, customer: Error message 2, ");


        const error3 = {
            message: "Error message 3",
            context: "order"
        }

        notification.addError(error3);
        expect(notification.messages("customer"))
            .toBe("customer: Error message, customer: Error message 2, ");



        expect(notification.messages())
            .toBe("customer: Error message, customer: Error message 2, order: Error message 3, ");

    });

    it("Should check if notification has errors", () => {
            
            const notification = new Notification();
    
            const error = {
                message: "Error message",
                context: "customer"
            }
    
            notification.addError(error);
    
            expect(notification.hasErrors()).toBe(true);
    
            const error2 = {
                message: "Error message 2",
                context: "customer"
            }
    
            notification.addError(error2);
            expect(notification.hasErrors()).toBe(true);
    });


    it("Should get all errors props", () => {
        const notification = new Notification();
        const error = {
            message: "Error message",
            context: "customer"
        }


        notification.addError(error);

        expect(notification.errors()).toEqual([error]);

    });


});