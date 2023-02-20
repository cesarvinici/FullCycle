import Order from "../entity/order";
import OrderFactory from "./order.factory";

describe("OrderFactory unit tests", () => {


    it("Should create an order", () => {
        const orderProps = {
            id: "123",
            customerId: "123",
            items: [
                {
                    id: "123",
                    name: "Item A",
                    price: 10,
                    quantity: 1,
                    ProductId: "123"
                }
            ],
        };


        const order = OrderFactory.create(orderProps);

        expect(order).toBeInstanceOf(Order);
        expect(order.id).toBe("123");
        expect(order.customerId).toBe("123");
        expect(order.items).toHaveLength(1);
        expect(order.items[0].id).toBe("123");
        expect(order.items[0].name).toBe("Item A");
        expect(order.items[0].price).toBe(10);
        expect(order.items[0].quantity).toBe(1);
        expect(order.items[0].productId).toBe("123");
    });

});