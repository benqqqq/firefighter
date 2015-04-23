import Image
import sys, getopt

def main(argv):
	opts, args = getopt.getopt(argv,"s:n:h:o:")
	print "[Resize image]"
	for opt, arg in opts:
		if opt == '-s':
			src = arg
			print 'src : ' + src
		if opt == '-n':
			name = arg
			print 'name : ' + name
		if opt == '-h':
			height = int(arg)
			print 'height : ' + str(height)
		if opt == '-o':
			out = arg
			print 'out : ' + out

	im = Image.open(src)
	print "ori size : "
	print im.size
	ratio = float(height)/im.size[1]
	width = int(im.size[0] * ratio)
	nim = im.resize((width, height), Image.BILINEAR)
	print "new size : "
	print nim.size
	nim.save(out + "/" + name + ".jpg")

if __name__ == "__main__":
	main(sys.argv[1:])