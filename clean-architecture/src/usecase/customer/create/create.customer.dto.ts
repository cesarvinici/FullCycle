import Address from "../../../domain/customer/value-object/address";

export interface InputCreateCustomerDto {
    name: string;
    address: {
        street: string,
        number: number,
        city: string,
        state: string, 
        zip: string,
    };   
}

export interface OutputCreateCustomerDto{
    id: string;
    name: string;
    address: {
        street: string,
        number: number,
        city: string,
        state: string, 
        zip: string,
    };    
}