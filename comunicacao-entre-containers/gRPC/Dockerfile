FROM golang

WORKDIR /var/app

RUN apt-get update -y && apt-get install -y protobuf-compiler && apt-get install -y sqlite3

# Installing gRPC
RUN go install google.golang.org/protobuf/cmd/protoc-gen-go@v1.28
RUN go install google.golang.org/grpc/cmd/protoc-gen-go-grpc@v1.2
RUN go install github.com/ktr0731/evans@latest
RUN export PATH="$PATH:$(go env GOPATH)/bin"
