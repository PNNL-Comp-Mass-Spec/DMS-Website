test( "dmsInput trim", function() {
  equal( dmsInput.trim(" Passed! "), "Passed!" );
});

test("dmsjs remove", function() {
	expect(1);
	var target = ['one', 'two', 'three', 'four', 'five'];
	var remove = ['two', 'four'];
	var expected = ['one', 'three', 'five'];
	var result = dmsjs.removeItems(target, remove);
	deepEqual( result, expected, "Array items successfully removed");
});
