import Product from "../../entity/product";
import SendEmailWhenProductIsCreatedHandler from "../product/handler/send-email-when-product-is-created.handler";
import ProductCreatedEvent from "../product/product-created.event";
import EventDispatcher from "./event-dispatcher";

describe("Domain events tests", () => {

    it("Should register an event handler", () => {

        const eventDispatcher = new EventDispatcher();
        const eventHandler = new SendEmailWhenProductIsCreatedHandler();

        eventDispatcher.register("ProductCreatedEvent", eventHandler);

        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"].length).toBe(1);
        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"][0]).toMatchObject(eventHandler);
    });

    it("Should unregister an event handler", () => {
            
            const eventDispatcher = new EventDispatcher();
            const eventHandler = new SendEmailWhenProductIsCreatedHandler();
    
            eventDispatcher.register("ProductCreatedEvent", eventHandler);

            expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeDefined();

            eventDispatcher.unregister("ProductCreatedEvent", eventHandler);
    
            expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeDefined();
            expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"].length).toBe(0);
    });


    it("Should unregister all event handlers", () => {
                
            const eventDispatcher = new EventDispatcher();
            const eventHandler = new SendEmailWhenProductIsCreatedHandler();
    
            eventDispatcher.register("ProductCreatedEvent", eventHandler);
    
            expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeDefined();
    
            eventDispatcher.unregisterAll();
    
            expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeUndefined();
    });

    it("Should notify an event", () => {

        const eventDispatcher = new EventDispatcher();
        const eventHandler = new SendEmailWhenProductIsCreatedHandler();
        const spyEventHandler = jest.spyOn(eventHandler, "handle");

        eventDispatcher.register("ProductCreatedEvent", eventHandler);

        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"]).toBeDefined();
        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"].length).toBe(1);
        expect(eventDispatcher.getEventHandlers["ProductCreatedEvent"][0]).toMatchObject(eventHandler);
        
        const product = new Product("123", "Product 1", 10);

        const productCreatedEvent = new ProductCreatedEvent(product);

        eventDispatcher.notify(productCreatedEvent);

        expect(spyEventHandler).toHaveBeenCalled();
    });
});