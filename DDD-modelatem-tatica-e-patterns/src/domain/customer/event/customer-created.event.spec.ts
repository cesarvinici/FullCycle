import Customer from "../entity/customer";
import EventDispatcher from "../../@shared/event/event-dispatcher";
import CustomerCreatedEvent from "./customer-created.event";
import EnviaConsoleLog1Handler from "./handler/enviaConsoleLog1.handler";
import EnviaConsoleLog2Handler from "./handler/enviaConsoleLog2.handler";

describe("Customer Created Events Unit Tests", () => {

    it( "Should register an event handler", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLog1Handler = new EnviaConsoleLog1Handler();
        const enviaConsoleLog2Handler = new EnviaConsoleLog2Handler();

        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog1Handler);
        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog2Handler);

        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"].length).toBe(2);
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"][0]).toMatchObject(enviaConsoleLog1Handler);
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"][1]).toMatchObject(enviaConsoleLog2Handler);
    });

    it( "Should unregister an event handler", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLog1Handler = new EnviaConsoleLog1Handler();
        const enviaConsoleLog2Handler = new EnviaConsoleLog2Handler();

        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog1Handler);
        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog2Handler);

        eventDispatcher.unregister("CustomerCreatedEvent", enviaConsoleLog1Handler);

        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"].length).toBe(1);
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"][0]).toMatchObject(enviaConsoleLog2Handler);
        expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"][1]).toBeUndefined();

    });

    it( "Should unregister all event handlers", () => {
            
            const eventDispatcher = new EventDispatcher();
            const enviaConsoleLog1Handler = new EnviaConsoleLog1Handler();
            const enviaConsoleLog2Handler = new EnviaConsoleLog2Handler();
    
            eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog1Handler);
            eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog2Handler);
    
            eventDispatcher.unregisterAll();
    
            expect(eventDispatcher.getEventHandlers["CustomerCreatedEvent"]).toBeUndefined();
    
    });

    it( "Should dispatch a CustomerCreatedEvent", () => {

        const eventDispatcher = new EventDispatcher();
        const enviaConsoleLog1Handler = new EnviaConsoleLog1Handler();
        const enviaConsoleLog2Handler = new EnviaConsoleLog2Handler();

        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog1Handler);
        eventDispatcher.register("CustomerCreatedEvent", enviaConsoleLog2Handler);

        const spyEventHandler1 = jest.spyOn(enviaConsoleLog1Handler, "handle");
        const spyEventHandler2 = jest.spyOn(enviaConsoleLog2Handler, "handle");
    

        const customer = new Customer("John", "Doe");

        const customerCreatedEvent = new CustomerCreatedEvent(customer);

        eventDispatcher.notify(customerCreatedEvent);

        expect(spyEventHandler1).toBeCalledTimes(1);
        expect(spyEventHandler2).toBeCalledTimes(1);
    });
});