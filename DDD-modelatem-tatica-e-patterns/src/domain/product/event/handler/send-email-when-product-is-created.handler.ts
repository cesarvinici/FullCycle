import EventHandlerInterface from "../../../@shared/event/event-handler.interface";
import ProductCreatedEvent from "../product-created.event";

export default class SendEmailWhenProductIsCreatedHandler implements EventHandlerInterface<ProductCreatedEvent> {
    handle(event: ProductCreatedEvent): void {
        console.log(`Send email to ..... with the product ${event.eventData.name}`);
    }
}