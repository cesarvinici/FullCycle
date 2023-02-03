import Order from "./order";
import OrderItem from "./order_items";

describe("Order unit tests", () => {

  it("Should throw error when id is empty", () => {
    expect(() => new Order("", "1", [])).toThrowError("Id is required");
  })

  it("Should throw error when customerId is empty", () => {
    expect(() => new Order("1", "", [])).toThrowError("CustomerId is required");
  });

  it("Should throw error if items is empty", () => {
    expect(() => new Order("1", "1", [])).toThrowError("Items are required");
  });

  it("Should calculate total", () => {
    const item = new OrderItem("1", "1", 100, "p1", 2);
    const order = new Order("1", "1", [item]);
    expect(order.total()).toBe(200);
    
    const item2 = new OrderItem("2", "2", 200, "p2", 2);
    const order2 = new Order("2", "2", [item, item2]);
    expect(order2.total()).toBe(600);
  });

  it ("Should check if the item quantity is greater than zero", () => {
    const item = new OrderItem("1", "1", 100, "p1", 0);
    expect(() => new Order("1", "1", [item])).toThrowError("Quantity must be greater than zero");
  });

  it("Should add item", () => {
    const item = new OrderItem("1", "1", 100, "p1", 2);
    const order = new Order("1", "1", [item]);
    const item2 = new OrderItem("2", "2", 200, "p2", 2);
    order.addItem(item2);
    expect(order.items.length).toBe(2);
  });

  it("Should remove item", () => {
    const item = new OrderItem("1", "1", 100, "p1", 2);
    const item2 = new OrderItem("2", "2", 200, "p2", 2);
    const order = new Order("1", "1", [item, item2]);
    order.removeItem("1");
    expect(order.items.length).toBe(1);
  });

});