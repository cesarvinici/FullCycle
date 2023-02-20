import Address from "../../customer/value-object/address";
import Customer from "../../customer/entity/customer";
import EventDispatcher from "../../@shared/event/event-dispatcher";
import CustomerChangedAddressEvent from "./customer-changed-address.event";
import EnviaConsoleLogAddressHandler from "./handler/enviaConsoleLogAddress.handler";

describe("Customer Created Events Unit Tests", () => {

    it( "Should register an event handler", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLogHandler = new EnviaConsoleLogAddressHandler();
        eventDispatcher.register("CustomerChangedAddress", enviaConsoleLogHandler);
        

        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"].length).toBe(1);
        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"][0]).toMatchObject(enviaConsoleLogHandler);
        
    });

    it( "Should unregister an event handler", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLogHandler = new EnviaConsoleLogAddressHandler();
        eventDispatcher.register("CustomerChangedAddress", enviaConsoleLogHandler);

        eventDispatcher.unregister("CustomerChangedAddress", enviaConsoleLogHandler);

        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"].length).toBe(0);

    });

    it( "Should unregister all event handlers", () => {
            
        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLogHandler = new EnviaConsoleLogAddressHandler();
        eventDispatcher.register("CustomerChangedAddress", enviaConsoleLogHandler);

        eventDispatcher.unregister("CustomerChangedAddress", enviaConsoleLogHandler);

        eventDispatcher.unregisterAll();

        expect(eventDispatcher.getEventHandlers["CustomerChangedAddress"]).toBeUndefined();
    
    });

    it( "Should dispatch a CustomerChangedAddress", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLogHandler = new EnviaConsoleLogAddressHandler();
        eventDispatcher.register("CustomerChangedAddressEvent", enviaConsoleLogHandler);

        const spyEventHandler = jest.spyOn(enviaConsoleLogHandler, "handle");
    
        const customer = new Customer("1", "John Doe");
        const address = new Address("Rua 1", 123, "São Paulo", "SP", "12345678");
        customer.changeAddress(address);

        const customerChangedAddressEvent = new CustomerChangedAddressEvent(customer);
        eventDispatcher.notify(customerChangedAddressEvent);

        expect(spyEventHandler).toBeCalledTimes(1);
        expect(spyEventHandler).toHaveBeenCalledWith(customerChangedAddressEvent);        
    });
});