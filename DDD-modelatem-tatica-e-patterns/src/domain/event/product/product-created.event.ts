import Product from "../../entity/product";
import EventInterface from "../@shared/event.interface";

export default class ProductCreatedEvent implements EventInterface {
    dataTimeOcurred: Date;
    eventData: Product;
    constructor(product: Product) {
        this.dataTimeOcurred = new Date();
        this.eventData = product;
    }
}