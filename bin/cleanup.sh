#!/bin/bash

CURRENT=$(cd $(dirname $0);pwd)
${CURRENT}/cake cleanup_temp_users
${CURRENT}/cake cleanup_password_forget_users
