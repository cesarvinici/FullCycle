import EventHandlerInterface from "../../../@shared/event/event-handler.interface";
import EventInterface from "../../../@shared/event/event.interface";

export default class EnviaConsoleLogAddressHandler implements EventHandlerInterface {
    handle(event: EventInterface): void {

        console.log(`Endereço do cliente: ${event.eventData.id}, ${event.eventData.name}, ${event.eventData.address.street}, ${event.eventData.address.number}, ${event.eventData.address.city}, ${event.eventData.address.state}, ${event.eventData.address.zipCode}`)
    }
}