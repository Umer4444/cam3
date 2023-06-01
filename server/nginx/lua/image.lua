local path = ngx.var.path
local full_path = ngx.var.root .. path

local function return_not_found(msg)
  ngx.status = ngx.HTTP_NOT_FOUND
  ngx.header["Content-type"] = "text/html"
  ngx.say(msg or "not found")
  ngx.exit(0)
end

-- make sure the file exists
local file = io.open(full_path)

if not file then
  return_not_found()
end

file:close()

-- resize the image
local magick = require("magick")
local img = magick.load_image(full_path);
local blurred = ngx.var.protected_dir .. path .. ".blurred"

os.execute("mkdir -p $(dirname " .. blurred .. ")")
img:blur(10, 0)
img:write(blurred)

ngx.exec(ngx.var.request_uri)