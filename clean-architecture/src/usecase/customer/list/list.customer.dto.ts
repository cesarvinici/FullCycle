export interface InputListCustomerDto {}


export type Customer = {
    id: string;
    name: string;
    address: {
        street: string;
        number: number;
        city: string;
        state: string;
        zip: string;
    }
}

export interface OutputListCustomerDto {
    customers: Customer[];
}