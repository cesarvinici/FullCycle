import Customer from "../../customer/entity/customer";
import EventInterface from "../../@shared/event/event.interface";

export default class CustomerCreatedEvent implements EventInterface {
    dataTimeOcurred: Date;
    eventData: Customer;
    constructor(customer: Customer) {
        this.dataTimeOcurred = new Date();
        this.eventData = customer;
    }
}