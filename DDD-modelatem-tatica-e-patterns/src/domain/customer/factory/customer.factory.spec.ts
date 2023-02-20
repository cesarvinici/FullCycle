import Customer from "../entity/customer";
import Address from "../value-object/address";
import CustomerFactory from "./customer.factory";

describe("CustomerFactory unit tests", () => {

    it("Should create a customer", () => {
        const customer = CustomerFactory.create("Customer A");

        expect(customer).toBeInstanceOf(Customer);
        expect(customer.id).toBeDefined();
        expect(customer.name).toBe("Customer A");
        expect(customer.constructor.name).toBe("Customer");
        expect(customer.address).toBeUndefined();
    });

    it("Should create a customer with address", () => {


        const address = new Address("Street A", 123, "City A", "State A", "1342123");
        const customer = CustomerFactory.createWithAddress("Customer A", address);

        expect(customer).toBeInstanceOf(Customer);
        expect(customer.id).toBeDefined();
        expect(customer.name).toBe("Customer A");
        expect(customer.constructor.name).toBe("Customer");
        expect(customer.address).toBe(address);
    });

});