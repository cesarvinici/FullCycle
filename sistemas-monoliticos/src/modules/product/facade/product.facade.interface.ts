export interface AddProductFacadeInputDto {
    id?: string;
    name: string;
    price: number;
}

export default interface ProductFacadeInterface {

    addProduct(input: AddProductFacadeInputDto): Promise<void>
}