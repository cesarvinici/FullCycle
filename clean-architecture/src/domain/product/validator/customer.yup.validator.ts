import ValidatorInterface from "../../@shared/validator/validator.interface";
import * as yup from "yup";
import Product from "../entity/product";

export default class ProductYupValidator implements ValidatorInterface<Product> {
    validate(entity: Product): void {
        
        try {
            const schema = yup.object().shape({
                id: yup.string().required("Id is required"),
                name: yup.string().required("Name is required"),
                price: yup.number().min(0, "Price must be greater than zero")
            });

            schema.validateSync({
                id: entity.id,
                name: entity.name,
                price: entity.price
            }, 
            { 
                abortEarly: false
            });

        } catch (errors) {
            
            const e = errors as yup.ValidationError;

            e.inner.forEach(error => {
                entity.notification.addError({
                    message: error.message,
                    context: "product"
                });
            });


        }


    }
}