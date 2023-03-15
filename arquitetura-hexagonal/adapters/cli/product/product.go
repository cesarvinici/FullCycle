package cli

import (
	"fmt"
	"go-hexagonal/application"
)

func Run(service application.ProductServiceInterface, action string, productId string, productName string, productPrice float64) (string, error) {

	var result = ""

	switch action {
	case "create":
		product, err := service.Create(productName, productPrice)
		if err != nil {
			return "", err
		}

		result = fmt.Sprintf("Created Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s", product.GetID(), product.GetName(), product.GetPrice(), product.GetStatus())

	case "enable":
		product, err := service.Get(productId)

		if err != nil {
			return "", err
		}

		res, err := service.Enable(product)

		if err != nil {
			return "", err
		}

		result = fmt.Sprintf("Enabled Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s", res.GetID(), res.GetName(), res.GetPrice(), res.GetStatus())
	case "disable":
		product, err := service.Get(productId)

		if err != nil {
			return "", err
		}

		res, err := service.Enable(product)

		if err != nil {
			return "", err
		}

		result = fmt.Sprintf("Disabled Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s", res.GetID(), res.GetName(), res.GetPrice(), res.GetStatus())
	default:
		res, err := service.Get(productId)

		if err != nil {
			return "", err
		}

		result = fmt.Sprintf("Product: \n\tId: %s \n\tName: %s \n\tPrice: %f \n\tStatus:%s", res.GetID(), res.GetName(), res.GetPrice(), res.GetStatus())
	}

	return result, nil

}
