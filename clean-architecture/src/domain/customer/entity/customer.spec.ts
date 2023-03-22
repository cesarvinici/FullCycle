import Address from "../value-object/address";
import Customer from "./customer";

describe("Customer unit tests", () => {

  it("Should throw error when id is empty", () => {
    expect(() => new Customer("", "John")).toThrowError("Id is required");
  });

  it("Should throw error when name is empty", () => {
    expect(() => new Customer("1", "")).toThrowError("Name is required");
  });


  it("Should change name", () => {
    const customer = new Customer("1", "John");
    customer.changeName("John Doe");
    expect(customer.name).toBe("John Doe");
  });

  it("Should activate customer", () => {

      const customer = new Customer("1", "John");
      const address = new Address("Street", 1, "City", "State", "zip-code");
      customer.changeAddress(address);

      customer.activate();
      expect(customer.isActive()).toBe(true);
  });


  it("Should deactivate customer", () => {
      
      const customer = new Customer("1", "John");
  
      customer.deactivate();
      expect(customer.isActive()).toBe(false);
  });

  it("Should throw error when address is empty and you try to activate a custmomer", () => {
      const customer = new Customer("1", "John");
      expect(() => customer.activate()).toThrowError("Address must not be empty");
  });

  it("Should add reward points", () => {
      const customer = new Customer("1", "John");
      expect(customer.rewardPoints).toBe(0);

      customer.addRewardPoints(10);
      expect(customer.rewardPoints).toBe(10);

      customer.addRewardPoints(10);
      expect(customer.rewardPoints).toBe(20);

  })
});