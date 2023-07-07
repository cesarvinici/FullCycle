export interface PaymentFacadeInputDto {
    orderId: string;
    amount: number;
}

export interface PaymentFacadeOutputDto {
    transactionId: string;
    orderId: string;
    amount: number;
    status: string;
    created_at: Date;
    updated_at: Date;
}

export default interface PaymentFacadeInterface {
    process(input: PaymentFacadeInputDto): Promise<PaymentFacadeOutputDto>;
}