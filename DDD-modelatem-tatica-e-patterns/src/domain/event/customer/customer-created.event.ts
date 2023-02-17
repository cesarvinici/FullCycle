import Customer from "../../entity/customer";
import EventInterface from "../@shared/event.interface";

export default class CustomerChangedAddressEvent implements EventInterface {
    dataTimeOcurred: Date;
    eventData: Customer;
    constructor(customer: Customer) {
        this.dataTimeOcurred = new Date();
        this.eventData = customer;
    }
}