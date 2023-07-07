export interface ProcessPaymentInputDTO {
    orderId: string;
    amount: number;
}

export interface ProcessPaymentOutputDTO {
    transactionId: string;
    orderId: string;
    amount: number;
    status: string;
    created_at: Date;
    updated_at: Date;
}
