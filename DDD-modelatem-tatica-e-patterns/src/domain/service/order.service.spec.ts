import Customer from "../entity/customer";
import Order from "../entity/order";
import OrderItem from "../entity/order_items";
import OrderService from "./order.service";

describe("Order service unit tests", () => {

    it("Should place an order", () => {
        const customer =  new Customer("1", "John");
        const item1 = new OrderItem("i1", "Item 1", 10, "p1", 1);

        const order = OrderService.placeOrder(customer, [item1]);

        expect(customer.rewardPoints).toBe(5);
        expect(order.total()).toBe(10);
    });


    it("Should get total of all orders", () => {
        
        const orderItem = new OrderItem("i1", "item 1", 100, "p1", 1);
        const orderItem2 = new OrderItem("i2", "item 2", 200, "p2", 1);
        const orderItem3 = new OrderItem("i3", "item 3", 300, "p3", 1);

        const order = new Order("o1", "c1", [orderItem]);
        const order2 = new Order("o2", "c2", [orderItem2]);
        const order3 = new Order("o3", "c3", [orderItem3]);

        const total = OrderService.getTotal([order, order2, order3]);
        
        expect(total).toBe(600);
    });
});