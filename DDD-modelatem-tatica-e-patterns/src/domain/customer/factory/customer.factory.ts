import Customer from "../entity/customer";
import Address from "../value-object/address";

export default class CustomerFactory {
    public static create(name: string): Customer {
        return new Customer(this.generateId(), name);
    }

    public static createWithAddress(name: string, address: Address): Customer {
        const customer = new Customer(this.generateId(), name);
        customer.changeAddress(address);
        return customer;
    }

    private static generateId(): string {
        return Math.random().toString(36).substr(2, 9);
    }
}