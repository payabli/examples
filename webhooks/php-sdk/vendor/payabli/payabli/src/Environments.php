<?php

namespace Payabli;

enum Environments: string
{
    case Sandbox = "https://api-sandbox.payabli.com/api";
    case Production = "https://api.payabli.com/api";
}
